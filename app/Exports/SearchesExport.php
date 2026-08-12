<?php

namespace App\Exports;

use App\Models\Search;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SearchesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Search::orderBy('count', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Search Keyword',
            'Search Count',
            'First Searched',
            'Last Updated',
        ];
    }

    public function map($search): array
    {
        return [
            $search->id,
            $search->query,
            $search->count,
            optional($search->created_at)->format('Y-m-d H:i:s'),
            optional($search->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
