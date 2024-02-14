<?php

namespace App\Exports\Admin;

use App\Exports\Admin\Sheets\RealCountPresidenSheets;
use App\Models\Dapil;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RealCountPresidenExport implements WithMultipleSheets
{

    public function sheets(): array
    {
        $sheets = [];
        $dapils = Dapil::all();
        foreach ($dapils as $dapil) {
            $sheets[] = new RealCountPresidenSheets($dapil->id);
        }

        return $sheets;
    }
}
