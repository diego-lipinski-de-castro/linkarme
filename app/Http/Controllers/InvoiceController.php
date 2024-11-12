<?php

namespace App\Http\Controllers;

use App\Filters\DateBetweenFilter;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
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
        $query = request()->query();

        $filters = [
            'search' => $query['filter']['search'] ?? null,
            'created_at' => $query['filter']['created_at'] ?? [],
        ];
        
        $invoices = QueryBuilder::for(Invoice::class)
            ->defaultSort('-created_at')
            ->allowedFilters([
                AllowedFilter::custom('created_at', new DateBetweenFilter),
            ])
            ->paginate()
            ->appends(request()->query());

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'filters' => $filters,
            'banks' => Invoice::BANKS,
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'selected' => ['required', 'array', 'min:1', 'max:100'],
            'selected.*' => ['exists:orders,id'],
        ]);

        /** @var Invoice $invoice */
        $invoice = Invoice::create([

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

    public function update(Invoice $invoice, Request $request)
    {
        $input = $request->validate([
            
        ]);

        $invoice->update($input);

        return back();
    }
}
