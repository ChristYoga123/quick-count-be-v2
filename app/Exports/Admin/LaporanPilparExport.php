<?php

namespace App\Exports\Admin;

use App\Models\Dapil;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPilparExport implements FromCollection, WithHeadings
{
    public Dapil $dapil;

    public function __construct(Dapil $dapil)
    {
        $this->dapil = $dapil;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('laporan_pilpars')
            ->select('laporan_pilpars.id', 'users.name', 'laporan_pilpars.laporan')
            ->join('users', 'laporan_pilpars.user_id', '=', 'users.id')
            ->join('pilpars', 'laporan_pilpars.pilpar_id', '=', 'pilpars.id')
            ->where('pilpars.dapil_id', $this->dapil->id)
            ->get();
    }

    public function headings(): array
    {
        return ['Id', 'Nama', 'Laporan'];
    }
}
