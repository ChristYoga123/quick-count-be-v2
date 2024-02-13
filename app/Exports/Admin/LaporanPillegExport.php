<?php

namespace App\Exports\Admin;

use App\Models\Dapil;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPillegExport implements FromCollection, WithHeadings
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
        return DB::table('laporan_pillegs')
            ->select('laporan_pillegs.id', 'users.name', 'laporan_pillegs.laporan')
            ->join('users', 'laporan_pillegs.user_id', '=', 'users.id')
            ->join('pillegs', 'laporan_pillegs.pilleg_id', '=', 'pillegs.id')
            ->where('pillegs.dapil_id', $this->dapil->id)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Laporan'
        ];
    }
}
