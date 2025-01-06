<?php

namespace App\Http\Controllers;

use App\Filters\DateBetweenFilter;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::query()
            ->orderBy('name')
            ->get();

        $query = request()->query();

        $filters = [
            'search' => $query['filter']['search'] ?? null,
            'client' => $query['filter']['client'] ?? null,
            'created_at' => $query['filter']['created_at'] ?? [],
        ];
        
        $invoices = QueryBuilder::for(Invoice::class)
            ->with([
                'client',
            ])
            ->defaultSort('-created_at')
            ->allowedFilters([
                AllowedFilter::scope('search', 'smart'),
                AllowedFilter::exact('client', 'client_id'),
                AllowedFilter::custom('created_at', new DateBetweenFilter),
            ])
            ->paginate(15)
            ->appends(request()->query());

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'filters' => $filters,
            'clients' => $clients,
            'banks' => Invoice::BANKS,
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'selected' => ['required', 'array', 'min:1', 'max:100'],
            'selected.*' => ['exists:orders,id'],
        ]);

        $order = Order::find($input['selected'][0]);

        /** @var Invoice $invoice */
        $invoice = Invoice::create([
            'client_id' => $order->client_id,
        ]);

        Order::query()
            ->whereIn('id', $input['selected'])
            ->update([
                'invoice_id' => $invoice->id,
            ]); 

        return redirect()->route('invoices.edit', $invoice->id);
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load([
            'orders',
        ]);

        return Inertia::render('Invoices/Edit', [
            'invoice' => $invoice,
            'banks' => Invoice::BANKS,
            'coins' => config('coins'),
        ]);
    }

    public function update(Invoice $invoice, UpdateInvoiceRequest $request)
    {
        $invoice->update($request->validated());

        return back();
    }

    public function generate(Invoice $invoice, Request $request)
    {
        return view('invoice_pdf', [
            'invoice' => $invoice,
        ]);

        $pdf = Pdf::view('invoice_pdf', [
            'invoice' => $invoice,
        ])
            ->format('A4')
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->setNodeBinary(config('pdf.node_path'));
                $browsershot->setNpmBinary(config('pdf.npm_path'));
                if (app()->environment('production')) {
                    $browsershot->setChromePath(config('pdf.chrome_path'));
                }
            })
            ->name('linkarme-invoice.pdf');

        return $pdf->download();
    }
}
