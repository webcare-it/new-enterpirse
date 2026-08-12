<?php

namespace App\Exports;

use App\Models\Admin\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Get all customers
     */
    public function collection()
    {
        return Customer::with('user')
            ->latest()
            ->get();
    }

    /**
     * Excel Headings
     */
    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Address',
            'Country',
            'City',
            'Postal Code',
            'Balance',
            'Status',
            'Joined Date',
        ];
    }

    /**
     * Map Data
     */
    public function map($customer): array
    {
        return [
            $customer->user->name ?? 'N/A',
            $customer->user->email ?? 'N/A',
            $customer->phone ?? 'N/A',
            $customer->address ?? 'N/A',
            $customer->country ?? 'N/A',
            $customer->city ?? 'N/A',
            $customer->postal_code ?? 'N/A',
            $customer->balance ?? 0,
            $customer->banned ? 'Banned' : 'Active',
            optional($customer->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
