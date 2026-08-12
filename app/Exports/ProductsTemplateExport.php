<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $headers;
    protected $sampleData;

    public function __construct($headers, $sampleData)
    {
        $this->headers = $headers;
        $this->sampleData = $sampleData;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function array(): array
    {
        return [
            $this->sampleData
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF'], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '4CAF50']]]],
        ];
    }
}
