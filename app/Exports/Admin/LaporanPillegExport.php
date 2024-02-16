<?php

namespace App\Exports\Admin;

use App\Exports\Admin\Sheets\LaporanPillegSheets;
use App\Models\Dapil;
use App\Models\Partai;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanPillegExport implements WithMultipleSheets
{
    public Dapil $dapil;

    public function __construct(Dapil $dapil)
    {
        $this->dapil = $dapil;
    }

    public function sheets(): array
    {
        $sheets = [];

        $partais = Partai::all();

        foreach ($partais as $partai) {
            $sheets[] = new LaporanPillegSheets($this->dapil, $partai);
        }

        return $sheets;
    }
}
