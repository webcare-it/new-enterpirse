<?php

namespace App\Exports;

use App\Models\Admin\Compare;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompareExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Compare::with(['user', 'product'])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'User Name',
            'User Email',
            'IP Address',
            'Product ID',
            'Product Name',
            'Added Date',
        ];
    }

    public function map($compare): array
    {
        return [
            $compare->user->name ?? 'Guest User',
            $compare->user->email ?? 'N/A',
            $compare->ip_address ?? 'N/A',
            $compare->product_id,
            $compare->product->name ?? 'N/A',
            optional($compare->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
