<?php

namespace App\Exports\Admin;

use App\Models\Dapil;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPilpresExport implements FromCollection, WithHeadings
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
        return DB::table('laporan_pilpres')
            ->select('laporan_pilpres.id', 'users.name', 'laporan_pilpres.laporan')
            ->join('users', 'laporan_pilpres.user_id', '=', 'users.id')
            ->join('pilpres', 'laporan_pilpres.pilpres_id', '=', 'pilpres.id')
            ->where('pilpres.dapil_id', $this->dapil->id)
            ->get();
    }

    public function headings(): array
    {
        return ['Id', 'Nama', 'Laporan'];
    }
}
