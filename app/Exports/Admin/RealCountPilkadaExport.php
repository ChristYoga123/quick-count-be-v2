<?php

namespace App\Exports\Admin;

use App\Exports\Admin\Sheets\RealCountPilkadaSheets;
use App\Models\Dapil;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RealCountPilkadaExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $dapils = Dapil::all();
        foreach ($dapils as $dapil) {
            $sheets[] = new RealCountPilkadaSheets($dapil->id);
        }

        return $sheets;
    }
}
