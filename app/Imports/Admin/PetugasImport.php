<?php

namespace App\Imports\Admin;

use App\Models\User;
use App\Models\UserCredential;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PetugasImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $petugas = User::firstOrCreate([
                'name' => $row['nama'],
                'email' => $row['email'],
                'password' => bcrypt($row['password']),
            ]);
            $petugas->assignRole([$row['roles']]);
            UserCredential::firstOrCreate([
                'user_id' => $petugas->id,
                'phone_number' => $row['telepon'],
            ]);
        }
    }
}
