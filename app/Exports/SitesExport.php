<?php

namespace App\Exports;

use App\Models\Site;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SitesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 10,
                    'color' => [
                        'argb' => Color::COLOR_WHITE,
                    ],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => Color::COLOR_BLUE,
                    ],
                ],
            ],

            // Styling a specific cell by coordinate.
            'B1' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => Color::COLOR_BLACK,
                    ],
                ],
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'INCLUSÃO',
            'ATUALIZAÇÃO',
            'Ativo',
            'URL',
            'DA',
            'DR',
            'TRÁFEGO',
            'RESPONSÁVEL',
            'C GERAL',
            'V GERAL',
            'C BETS',
            'V BETS',
            'C CBD',
            'V CBD',
            'C CRIP',
            'V CRIP',
            'C Dating',
            'V Dating',
            'C EROTIC',
            'V EROTIC',
            'Sug Valor',
            'Notas',
            'PUBLI',
            'OBSERVAÇÃO',
            'País',
            'Linguagem',
            'CATEGORIAS',
            'Dono do Site',
            'Email',
            'Whats',
            'Telefone',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 100,
            'B' => 100,
            'C' => 100,
            'D' => 100,
            'E' => 100,
            'F' => 100,
            'G' => 100,      
            'H' => 100,
            'I' => 100,
            'J' => 100,
            'K' => 100,
            'L' => 100,
            'M' => 100,
            'N' => 100,
            'O' => 100,
            'P' => 100,
            'Q' => 100,
            'R' => 100,
            'S' => 100,
            'T' => 100,
            'U' => 100,
            'V' => 100,
            'W' => 100,
            'X' => 100,
            'Y' => 100,
            'Z' => 100,
            'AA' => 100,
            'AB' => 100,
            'AC' => 100,
            'AD' => 100,
            'AE' => 100,
        ];
    }

    public function map($row): array
    {

        return [
            'INCLUSÃO',
            'ATUALIZAÇÃO',
            'Ativo',
            Str::of($row->url)->trim(),
            $row->da ?? '-',
            $row->dr ?? '-',
            'TRÁFEGO',
            'RESPONSÁVEL',
            'C GERAL',
            'V GERAL',
            'C BETS',
            'V BETS',
            'C CBD',
            'V CBD',
            'C CRIP',
            'V CRIP',
            'C Dating',
            'V Dating',
            'C EROTIC',
            'V EROTIC',
            'Sug Valor',
            'Notas',
            'PUBLI',
            'OBSERVAÇÃO',
            Str::of($row->country?->name ?? '-')->trim(),
            Str::of($row->language?->name ?? '-')->trim(),
            Str::of($row->category?->name ?? '-')->trim(),
            Str::of($row->owner_name ?? '-')->trim(),
            Str::of($row->owner_email ?? '-')->trim(),
            Str::of($row->owner_phone ?? '-')->trim(),
            Str::of($row->owner_phone ?? '-')->trim(),
        ];
    }

    public function query()
    {
        return Site::query()
            ->withTrashed()
            ->ofStatus('PENDING');
    }
}
