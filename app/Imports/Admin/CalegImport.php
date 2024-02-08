<?php

namespace App\Imports\Admin;

use App\Models\Caleg;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CalegImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $caleg = Caleg::create([
                'no_urut' => $row['urutan'],
                'nama' => $row['nama'],
                'dapil_id' => $row['dapil'],
                'partai_id' => $row['partai'],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'urutan' => 'required',
            'nama' => 'required',
            'dapil' => 'required|exists:dapils,id',
            'partai' => 'required|exists:partais,id',
        ];
    }
}
