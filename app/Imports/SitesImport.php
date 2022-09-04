<?php

namespace App\Imports;

use App\Helper;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Seller;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

class SitesImport implements ToModel, WithHeadingRow, WithUpserts, WithValidation, WithBatchInserts, SkipsOnError, SkipsOnFailure
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
        $costCoin = 'BRL';
        $saleCoin = 'BRL';

        if (! blank($row['custo']) && Str::contains($row['custo'], ['USD', 'usd', 'dolar', 'Dolares'])) {
            $costCoin = 'USD';
        } elseif (! blank($row['custo']) && Str::contains($row['custo'], ['EUR', 'eur', 'euros', '€'])) {
            $costCoin = 'EUR';
        }

        if (! blank($row['venda']) && Str::contains($row['venda'], ['USD', 'usd', 'dolar', 'Dolares'])) {
            $saleCoin = 'USD';
        } elseif (! blank($row['venda']) && Str::contains($row['venda'], ['EUR', 'eur', 'euros', '€'])) {
            $saleCoin = 'EUR';
        }

        $custo = $row['custo'];
        $venda = $row['venda'];

        if (! Str::contains($custo, ',')) {
            $custo .= '00';
        }

        if (! Str::contains($venda, ',')) {
            $venda .= '00';
        }

        $custo = Helper::extractNumbersFromString($custo);
        $venda = Helper::extractNumbersFromString($venda);

        $url = Str::contains($row['dominio'], '://') ?
            str_replace('www.', '', parse_url($row['dominio'], PHP_URL_HOST)) :
            str_replace('www.', '', parse_url($row['dominio'], PHP_URL_PATH));

        $country = null;
        $language = null;
        $category = null;

        $country = Country::firstWhere('name', $row['pais']);

        if (! blank($row['linguagem'])) {
            $language = Language::firstOrCreate([
                'name' => $row['linguagem'],
            ]);
        }

        if (! blank($row['categorias'])) {
            $category = Category::firstOrCreate([
                'name' => $row['categorias'],
            ]);
        }

        if (! blank($row['atendimento'])) {
            $seller = Seller::where('name', 'LIKE', '%'.$row['atendimento'].'%')->first();

            if (blank($seller)) {
                $seller = Seller::create([
                    'name' => $row['atendimento'],
                ]);
            }
        }

        return new Site([
            'url' => $url,
            'name' => null,
            'description' => null,
            'obs' => $row['observacoes'],
            'admin_obs' => $row['notas'],
            'da' => $row['da'],
            'dr' => $row['dr'],
            'traffic' => null,
            'tf' => null,
            'language_id' => optional($language)->id,
            'country_id' => optional($country)->id,
            'category_id' => optional($category)->id,
            'link_type' => 'DOFOLLOW',
            'gambling' => strtolower($row['cassinos']) == 'sim' ? true : false,
            'cdb' => false,
            'cripto' => strtolower($row['cripto']) == 'sim' ? true : false,
            'sponsor' => strtolower($row['tag_publi']) == 'sim' ? true : false,
            'ssl' => false,
            'broken' => false,
            'cost' => $custo,
            'sale' => $venda,
            'cost_coin' => $costCoin,
            'sale_coin' => $saleCoin,
            // 'last_posted' => Carbon::createFromFormat('d/m/Y', $row['inclusao'])->format('Y-m-d'),
            'inserted_at' => Carbon::createFromFormat('d/m/Y', $row['inclusao'])->format('Y-m-d'),
            'seller_id' => optional($seller)->id,

            'menu' => strtolower($row['link_menu']) == 'sim' ? true : false,
            'banner' => strtolower($row['banners']) == 'sim' ? true : false,

            'owner_name' => $row['dono_do_site'],
            'owner_email' => $row['email'],
            'owner_phone' => $row['whats'],

            'bank' => $row['dados_bancarios'],
            'pix' => $row['pix'],

            'phone' => $row['telefone'],
            'paypal' => $row['paypal'],
            'instagram' => $row['instagram'],
            'facebook' => $row['facebook'],

            'deleted_at' => strtolower($row['ativo']) == 'sim' ? null : now(),
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
            'cassinos' => ['nullable', Rule::in(['sim', 'Sim', 'Não'])],
            'cripto' => ['nullable', Rule::in(['sim', 'Sim', 'Não'])],
            'tag_publi' => ['nullable', Rule::in(['sim', 'Sim', 'Não'])],
            'notas' => [],
            'observacoes' => [],

            'link_menu' => ['nullable', Rule::in(['sim', 'Sim', 'Não'])],
            'banners' => ['nullable', Rule::in(['sim', 'Sim', 'Não'])],

            'dono_do_site' => [],
            'email' => [],
            'whats' => [],

            'dados_bancarios' => [],
            'pix' => [],

            'telefone' => [],
            'paypal' => [],
            'instagram' => [],
            'facebook' => [],

            'ativo' => ['nullable', Rule::in(['sim', 'Sim', 'Não'])],
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
