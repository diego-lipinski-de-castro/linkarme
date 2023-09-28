<?php

namespace App\Imports;

use App\Helper;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Seller;
use App\Models\Site;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class SitesImport implements OnEachRow, WithHeadingRow, SkipsOnError, SkipsOnFailure, WithValidation
{
    use Importable;
    use SkipsFailures;
    use SkipsErrors;

    protected $notifyAdded;
    protected $notifyUpdated;

    public function __construct(bool $notifyAdded = false, bool $notifyUpdated = false)
    {
        $this->notifyAdded = $notifyAdded;
        $this->notifyUpdated = $notifyUpdated;
    }

    protected function getCoin($value) {
        if(blank($value)) return null;

        $coin = 'BRL';

        if (! blank($value) && Str::contains($value, ['USD', 'usd', 'dolar', 'Dolares', '$'])) {
            $coin = 'USD';
        } elseif (! blank($value) && Str::contains($value, ['EUR', 'eur', 'euros', '€'])) {
            $coin = 'EUR';
        }

        return $coin;
    }

    protected function getValue($value) {
        if(blank($value)) return null;

        if (! Str::contains($value, ',')) {
            $value .= '00';
        }

        $value = Helper::extractNumbersFromString($value);

        return $value;
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $url = Str::contains($row['url'], '://') ?
            str_replace('www.', '', parse_url($row['url'], PHP_URL_HOST)) :
            str_replace('www.', '', parse_url($row['url'], PHP_URL_PATH));

        $country = null;
        $language = null;
        $category = null;

        if (! blank($row['pais'])) {
            $country = Country::firstOrCreate([
                'name' => $row['pais'],
            ]);
        }

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

        // if (! blank($row['atendimento'])) {
        //     $seller = Seller::where('name', 'LIKE', '%'.$row['atendimento'].'%')->first();

        //     if (blank($seller)) {
        //         $seller = Seller::create([
        //             'name' => $row['atendimento'],
        //         ]);
        //     }
        // }

        if (! blank($row['responsavel'])) {
            $team = Team::where('name', 'LIKE', '%'.$row['responsavel'].'%')->first();

            if (blank($team)) {
                $team = Team::create([
                    'name' => $row['responsavel'],
                ]);
            }
        }

        $site = Site::withTrashed()->where('url', $url)->first();

        if(!blank($site)) {

            if(!$this->notifyUpdated) {
                Site::disableAuditing();
            }

            $site->update([
                'name' => null,
                'description' => null,
                'obs' => $row['observacoes'],
                // 'admin_obs' => $row['notas'],
                'da' => $row['da'],
                'dr' => $row['dr'],
                'traffic' => $row['trafego'],
                'tf' => null,
                'language_id' => optional($language)->id,
                'country_id' => optional($country)->id,
                'category_id' => optional($category)->id,
                'link_type' => 'DOFOLLOW',
                // 'gambling' => strtolower($row['cassinos']) == 'sim' ? true : false,
                // 'cdb' => false,
                // 'cripto' => strtolower($row['cripto']) == 'sim' ? true : false,
                'sponsor' => strtolower($row['publi']) == 'sim' ? true : false,
                'ssl' => false,
                'broken' => false,
                'cost' => $this->getValue($row['c_geral']),
                'sale' => $this->getValue($row['v_geral']),
                'cost_coin' => $this->getCoin($row['c_geral']),
                'sale_coin' => $this->getCoin($row['v_geral']),
                // 'last_posted' => Carbon::createFromFormat('d/m/Y', $row['inclusao'])->format('Y-m-d'),
                'inserted_at' => blank($row['inclusao']) ? now() : Carbon::createFromFormat('d/m/Y', $row['inclusao'])->format('Y-m-d'),
                'last_updated_at' => blank($row['atualizacao']) ? null : Carbon::createFromFormat('d/m/Y', $row['atualizacao'])->format('Y-m-d'),
                // 'seller_id' => optional($seller)->id,
                'team_id' => optional($team)->id,

                // 'menu' => strtolower($row['link_menu']) == 'sim' ? true : false,
                // 'banner' => strtolower($row['banners']) == 'sim' ? true : false,

                'owner_name' => optional($row)['dono_do_site'],
                'owner_email' => optional($row)['email'],
                'owner_phone' => optional($row)['whats'],

                'bank' => optional($row)['dados_bancarios'],
                'pix' => optional($row)['pix'],

                'phone' => optional($row)['telefone'],
                'paypal' => optional($row)['paypal'],
                'instagram' => optional($row)['instagram'],
                'facebook' => optional($row)['facebook'],

                'deleted_at' => strtolower($row['ativo']) == 'sim' ? null : now(),

                'status' => 'APPROVED',
            ]);

            if(!$this->notifyUpdated) {
                Site::enableAuditing();
            }

        } else {

            $site = Site::create([
                'url' => $url,
                'name' => null,
                'description' => null,
                'obs' => $row['observacoes'],
                // 'admin_obs' => $row['notas'],
                'da' => $row['da'],
                'dr' => $row['dr'],
                'traffic' => $row['trafego'],
                'tf' => null,
                'language_id' => optional($language)->id,
                'country_id' => optional($country)->id,
                'category_id' => optional($category)->id,
                'link_type' => 'DOFOLLOW',
                // 'gambling' => strtolower($row['cassinos']) == 'sim' ? true : false,
                // 'cdb' => false,
                // 'cripto' => strtolower($row['cripto']) == 'sim' ? true : false,
                'sponsor' => strtolower($row['publi']) == 'sim' ? true : false,
                'ssl' => false,
                'broken' => false,
                'cost' => $this->getValue($row['c_geral']),
                'sale' => $this->getValue($row['v_geral']),
                'cost_coin' => $this->getCoin($row['c_geral']),
                'sale_coin' => $this->getCoin($row['v_geral']),
                // 'last_posted' => Carbon::createFromFormat('d/m/Y', $row['inclusao'])->format('Y-m-d'),
                'inserted_at' => blank($row['inclusao']) ? now() : Carbon::createFromFormat('d/m/Y', $row['inclusao'])->format('Y-m-d'),
                'last_updated_at' => blank($row['atualizacao']) ? null : Carbon::createFromFormat('d/m/Y', $row['atualizacao'])->format('Y-m-d'),
                // 'seller_id' => optional($seller)->id,
                'team_id' => optional($team)->id,

                // 'menu' => strtolower($row['link_menu']) == 'sim' ? true : false,
                // 'banner' => strtolower($row['banners']) == 'sim' ? true : false,

                'owner_name' => optional($row)['dono_do_site'],
                'owner_email' => optional($row)['email'],
                'owner_phone' => optional($row)['whats'],

                'bank' => optional($row)['dados_bancarios'],
                'pix' => optional($row)['pix'],

                'phone' => optional($row)['telefone'],
                'paypal' => optional($row)['paypal'],
                'instagram' => optional($row)['instagram'],
                'facebook' => optional($row)['facebook'],

                'deleted_at' => strtolower($row['ativo']) == 'sim' ? null : now(),

                'status' => 'PENDING',
            ]);

            if(!$this->notifyAdded) {
                Site::disableAuditing();
            }

            $site->update([
                'status' => 'APPROVED',
            ]);

            if(!$this->notifyAdded) {
                Site::enableAuditing();
            }
        }

        /**
         * IDS:
         * 1 - cassinos
         * 2 - cbd
         * 3 - crypto
         * 4 - dating
         * 5 - erotic
         * 6 - bets
         */

        $types = [];

        $types[1] = [
            'cost' => $this->getValue($row['c_cassino']),
            'sale' => $this->getValue($row['v_cassino']),
            'cost_coin' => $this->getCoin($row['c_cassino']),
            'sale_coin' => $this->getCoin($row['v_cassino']),
        ];

        $types[2] = [
            'cost' => $this->getValue($row['c_cbd']),
            'sale' => $this->getValue($row['v_cbd']),
            'cost_coin' => $this->getCoin($row['c_cbd']),
            'sale_coin' => $this->getCoin($row['v_cbd']),
        ];

        $types[3] = [
            'cost' => $this->getValue($row['c_crip']),
            'sale' => $this->getValue($row['v_crip']),
            'cost_coin' => $this->getCoin($row['c_crip']),
            'sale_coin' => $this->getCoin($row['v_crip']),
        ];

        $types[4] = [
            'cost' => $this->getValue($row['c_dating']),
            'sale' => $this->getValue($row['v_dating']),
            'cost_coin' => $this->getCoin($row['c_dating']),
            'sale_coin' => $this->getCoin($row['v_dating']),
        ];

        $types[5] = [
            'cost' => $this->getValue($row['c_erotic']),
            'sale' => $this->getValue($row['v_erotic']),
            'cost_coin' => $this->getCoin($row['c_erotic']),
            'sale_coin' => $this->getCoin($row['v_erotic']),
        ];

        $types[6] = [
            'cost' => $this->getValue($row['c_bets']),
            'sale' => $this->getValue($row['v_bets']),
            'cost_coin' => $this->getCoin($row['c_bets']),
            'sale_coin' => $this->getCoin($row['v_bets']),
        ];

        $types = array_filter($types, function ($type) {
            return
                !is_null($type['cost']) && 
                !is_null($type['sale']) && 
                !is_null($type['cost_coin']) && 
                !is_null($type['sale_coin']);
        });

        $site->types()->sync($types);
    }

    public function rules(): array
    {
        return [
            'inclusao' => ['nullable', 'date_format:d/m/Y'],
            'atualizacao' => ['nullable', 'date_format:d/m/Y'],
            'url' => ['required'],
            'da' => ['nullable', 'integer'],
            'dr' => ['nullable', 'integer'],
            'trafego' => ['nullable', 'integer'],
            // 'atendimento' => [],
            'responsavel' => [],
            'custo' => [],
            'venda' => [],
            'categorias' => [],
            'pais' => [],
            'linguagem' => [],
            // 'cassinos' => ['nullable', Rule::in(['sim', 'Sim', 'Não', 'não'])],
            // 'cripto' => ['nullable', Rule::in(['sim', 'Sim', 'Não', 'não'])],
            'publi' => ['nullable', Rule::in(['sim', 'Sim', 'Não', 'não'])],
            'notas' => [],
            'observacoes' => [],

            // 'link_menu' => ['nullable', Rule::in(['sim', 'Sim', 'Não', 'não'])],
            // 'banners' => ['nullable', Rule::in(['sim', 'Sim', 'Não', 'não'])],

            'dono_do_site' => [],
            'email' => [],
            'whats' => [],

            'dados_bancarios' => [],
            'pix' => [],

            'telefone' => [],
            'paypal' => [],
            'instagram' => [],
            'facebook' => [],

            'ativo' => ['nullable', Rule::in(['sim', 'Sim', 'SIM', 'Não'])],

            'c_geral' => [],
            'v_geral' => [],

            'c_bets' => [],
            'v_bets' => [],

            'c_cbd' => [],
            'v_cbd' => [],

            'c_crip' => [],
            'v_crip' => [],

            'c_dating' => [],
            'v_dating' => [],

            'c_erotic' => [],
            'v_erotic' => [],

            // 'c_erotic' => [],
            // 'v_erotic' => [],
        ];
    }
}
