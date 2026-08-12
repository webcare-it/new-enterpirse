<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrdersTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
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
        return [$this->sampleData];
    }
}
