<?php

namespace App\Imports;

use App\Helper;
use App\Models\Site;
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

class SitesImport implements ToModel, WithHeadingRow, WithUpserts, WithValidation, SkipsOnError, SkipsOnFailure
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
            'url' => $row['dominio'],
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
        ]);
    }

    public function rules(): array
    {
        return [
            'dominio' => [],
            'observacoes' => [],
            'da' => ['nullable', 'integer'],
            'dr' => ['nullable', 'integer'],
            'cassinos' => [Rule::in(['Sim', 'Não'])],
            'custo' => [],
            'venda' => [],
            'atendimento' => [],
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