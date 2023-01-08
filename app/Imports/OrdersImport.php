<?php

namespace App\Imports;

use App\Helper;
use App\Models\Client;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class OrdersImport implements ToModel, WithHeadingRow, WithUpserts, WithValidation, SkipsOnFailure, SkipsOnError, WithBatchInserts
{
    use Importable;
    use SkipsFailures;
    use SkipsErrors;

    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $url = $row['url'];

        // $domain = Str::contains($row['url'], '://') ?
        //     str_replace('www.', '', parse_url($row['url'], PHP_URL_HOST)) :
        //     str_replace('www.', '', parse_url('http://' . $row['url'], PHP_URL_HOST));

        // $site = Site::query()
        //     ->withTrashed()
        //     ->where('url', $domain)
        //     ->first();

        $site = Site::query()
            ->withTrashed()
            ->whereRaw("LOCATE(sites.url, '$url') > 0")
            ->first();

        if (blank($site)) {
            Log::debug("SITE NOT FOUND FOR URL: $url");

            return;
        }

        $status = match ($row['status']) {
            // '' => 'WAITING',
            // '' => 'PRODUCTION',
            // '' => 'SUBMITTED',
            // '' => 'PROCESSING',
            // '' => 'PUBLISHED',
            // '' => 'INVOICE',
            'FINALIZADO' => 'COMPLETED',
        };

        $charged = $row['valor_cobrado'];
        $paid = $row['valor_pago'];
        $comission = $row['comissao'];

        if (! Str::contains($charged, ',')) {
            $charged .= '00';
        }

        if (! Str::contains($paid, ',')) {
            $paid .= '00';
        }

        if (! Str::contains($comission, ',')) {
            $comission .= '00';
        }

        $charged = Helper::extractNumbersFromString($charged);
        $paid = Helper::extractNumbersFromString($paid);
        $comission = Helper::extractNumbersFromString($comission);

        $client = null;
        $seller = null;

        if (! blank($row['cliente'])) {
            $client = Client::where('name', 'LIKE', '%'.$row['cliente'].'%')->first();

            if (blank($client)) {
                Log::debug('add client: ' . $row['cliente']);

                $client = Client::create([
                    'name' => $row['cliente'],
                ]);
            }
        }

        if (! blank($row['vendedor'])) {
            $seller = Seller::where('name', 'LIKE', '%'.$row['vendedor'].'%')->first();

            if (blank($seller)) {
                Log::debug('add seller: ' . $row['vendedor']);

                $seller = Seller::create([
                    'name' => $row['vendedor'],
                ]);
            }
        }

        return new Order([
            'site_id' => $site->id,
            'client_id' => optional($client)->id,
            'seller_id' => optional($seller)->id,
            'url' => $row['url'],
            'broken' => false,
            'ssl' => false,
            'receipt_date' => Carbon::createFromFormat('d/m/Y', $row['data_de_recebimento'])->format('Y-m-d'),
            'delivery_date' => Carbon::createFromFormat('d/m/Y', $row['data_de_entrega'])->format('Y-m-d'),
            'payment_date' => Carbon::createFromFormat('d/m/Y', $row['data_do_pagamento'])->format('Y-m-d'),
            'charged' => $charged,
            'paid' => $paid,
            'markup' => $charged - $paid,
            'comission' => $comission,
            'company' => $row['empresa'], 
            'status' => $status,
        ]);
    }

    public function rules(): array
    {
        return [
            'status' => [],
            'cliente' => [],
            'vendedor' => [],
            'url' => ['required'],
            'data_de_recebimento' => ['required', 'date_format:d/m/Y'],
            'data_de_entrega' => ['required', 'date_format:d/m/Y'],
            'data_do_pagamento' => ['required', 'date_format:d/m/Y'],
            'valor_cobrado' => ['required'],
            'valor_pago' => ['required'],
            'comissao' => ['required'],
            'empresa' => [],
        ];
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'url';
    }
}
