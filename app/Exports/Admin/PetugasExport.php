<?php

namespace App\Exports\Admin;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PetugasExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('users')
            ->select('users.name', 'users.email', 'user_credentials.phone_number', 'roles.name as roles')
            ->join('user_credentials', 'users.id', '=', 'user_credentials.user_id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Telp',
            'Roles'
        ];
    }
}
