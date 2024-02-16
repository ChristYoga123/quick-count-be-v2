<?php

namespace App\Exports\Admin\Sheets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanPillegSheets implements FromCollection, WithTitle
{
    public $dapil, $partai;

    public function __construct($dapil, $partai)
    {
        $this->dapil = $dapil;
        $this->partai = $partai;
    }

    public function collection()
    {
        $realCountPilleg = DB::table('suara_pillegs')
            ->select('users.email', 'users.name', 'dapils.index', 'pillegs.kelurahan', 'pillegs.tps', 'pillegs.jumlah_dpt', 'pillegs.hasil_suara_tidak_sah', 'suara_pillegs.caleg_id', 'suara_pillegs.jumlah_suara', 'pillegs.id as pillegs_id')
            ->join('laporan_pillegs', 'suara_pillegs.pilleg_id', '=', 'laporan_pillegs.pilleg_id')
            ->join('pillegs', 'suara_pillegs.pilleg_id', '=', 'pillegs.id')
            ->join('users', 'laporan_pillegs.user_id', '=', 'users.id')
            ->join('dapils', 'pillegs.dapil_id', '=', 'dapils.id')
            ->join('calegs', 'suara_pillegs.caleg_id', '=', 'calegs.id')
            ->where('pillegs.dapil_id', $this->dapil->id)
            ->where('calegs.partai_id', $this->partai->id)
            ->get();

        $realCountPilleg = $realCountPilleg->reduce(function ($carry, $item) {
            if (!isset($carry[$item->pillegs_id])) {
                $carry[$item->pillegs_id] = [
                    'email' => $item->email,
                    'name' => $item->name,
                    'index' => $item->index,
                    'kelurahan' => $item->kelurahan,
                    'tps' => $item->tps,
                    'jumlah_dpt' => $item->jumlah_dpt,
                    'hasil_suara_tidak_sah' => $item->hasil_suara_tidak_sah
                ];
            }
            $carry[$item->pillegs_id][$item->caleg_id] = $item->jumlah_suara;
            return $carry;
        }, []);

        return collect($realCountPilleg);
    }

    public function title(): string
    {
        $partai = DB::table('partais')->where('id', $this->partai->id)->first();
        return $partai->nama;
    }
}
