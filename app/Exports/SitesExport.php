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
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SitesExport implements FromQuery, WithHeadings, WithMapping, WithDefaultStyles, WithStyles, WithColumnWidths, ShouldAutoSize
{
    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'E:G' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'K' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFB7E1CD',
                    ],
                ],
            ],
            'M:N' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFC0C0C0',
                    ],
                ],
            ],
            'Q:R' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFC0C0C0',
                    ],
                ],
            ],
            'U:V' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFC0C0C0',
                    ],
                ],
            ],
            'Y:Z' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFC0C0C0',
                    ],
                ],
            ],
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
                        'argb' => Color::COLOR_BLACK,
                    ],
                ],
            ],
            'A1:AE10' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'argb' => Color::COLOR_BLACK
                    ],
                ],
            ],
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => [
                    'argb' => Color::COLOR_BLACK
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
            'PUBLI',
            'URL',
            'OBSERVAÇÕES',
            'Notas',
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
            'H' => 8,
            'I' => 8,
        ];
    }

    public function map($row): array
    {

        return [
            blank($row->inserted_at) ? $row->created_at->format('d/m/Y') : $row->inserted_at->format('d/m/Y'),
            $row->updated_at->format('d/m/Y'),
            'Ativo',
            'PUBLI',
            $row->url,
            'OBSERVAÇÕES',
            'Notas',
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
            $row->country?->name ?? '-',
            $row->language?->name ?? '-',
            $row->category?->name ?? '-',
            $row->owner_name ?? '-',
            $row->owner_email ?? '-',
            $row->owner_phone ?? '-',
            $row->owner_phone ?? '-',
        ];
    }

    public function query()
    {
        return Site::query()
            ->withTrashed()
            ->ofStatus('PENDING');
    }
}
