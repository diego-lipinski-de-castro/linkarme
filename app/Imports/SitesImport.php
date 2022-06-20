<?php

namespace App\Imports;

use App\Helper;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class SitesImport implements ToModel, WithHeadingRow, WithUpserts, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable;
    use SkipsFailures;
    use SkipsErrors;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $costCoin = 'BRL';
        $saleCoin = 'BRL';

        if(!blank($row['custo']) && Str::contains($row['custo'], ['USD', 'usd', 'dolar', 'Dolares'])) {
            $costCoin = 'USD';
        } else if(!blank($row['custo']) && Str::contains($row['custo'], ['EUR', 'eur', 'euros', '€'])) {
            $costCoin = 'EUR';
        }

        if(!blank($row['venda']) && Str::contains($row['venda'], ['USD', 'usd', 'dolar', 'Dolares'])) {
            $saleCoin = 'USD';
        } else if(!blank($row['venda']) && Str::contains($row['venda'], ['EUR', 'eur', 'euros', '€'])) {
            $saleCoin = 'EUR';
        }

        $custo = $row['custo'];
        $venda = $row['venda'];

        if(!Str::contains($custo, ',')) {
            $custo .= '00';
        }

        if(!Str::contains($venda, ',')) {
            $venda .= '00';
        }

        $custo = Helper::extractNumbersFromString($custo);
        $venda = Helper::extractNumbersFromString($venda);

        return new Site([
            'url' => str_replace('www.', '', parse_url($row['dominio'])),
            'name' => null,
            'description' => null,
            'obs' => $row['observacoes'],
            'da' => $row['da'],
            'dr' => $row['dr'],
            'traffic' => null,
            'tf' => null,
            'language_id' => null,
            'country_id' => null,
            'category_id' => null,
            'link_type' => 'NOFOLLOW',
            'gambling' => $row['cassinos'] == 'Sim' ? true : false,
            'cdb' => false,
            'cripto' => false,
            'sponsor' => false,
            'ssl' => false,
            'broken' => false,
            'cost' => $custo,
            'sale' => $venda,
            'cost_coin' => $costCoin,
            'sale_coin' => $saleCoin,
            'last_posted' => null,
            'owner_name' => $row['atendimento'],
            'owner_whatsapp' => null,
            'inserted_at' => Carbon::createFromFormat('d/m/Y', $row['inclusao'])->format('Y-m-d'),
        ]);
    }

    public function rules(): array
    {
        return [
            'inclusao' => ['required', 'date_format:d/m/Y'],
            'dominio' => [],
            'da' => ['nullable', 'integer'],
            'dr' => ['nullable', 'integer'],
            'atendimento' => [],
            'custo' => [],
            'venda' => [],
            'categorias' => [],
            'pais' => [],
            'linguagem' => [],
            'cassinos' => [Rule::in(['Sim', 'Não'])],
            'cripto' => [Rule::in(['Sim', 'Não'])],
            'tag_publi' => [Rule::in(['Sim', 'Não'])],
            'notas' => [],
            'observacoes' => [],
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
