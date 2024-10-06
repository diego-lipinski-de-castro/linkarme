<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::query()
            ->paginate();

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
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
        ]);
    }
}
