<?php

namespace App\Exports\Admin;

use App\Models\Dapil;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPilpresExport implements FromCollection
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
        $realCountPresiden = DB::table('suara_pilpres')
            ->select('users.email', 'users.name', 'dapils.index', 'pilpres.kelurahan', 'pilpres.tps', 'pilpres.jumlah_dpt', 'pilpres.hasil_suara_tidak_sah', 'suara_pilpres.capres_id', 'suara_pilpres.jumlah_suara', 'pilpres.id as pilpres_id')
            ->join('laporan_pilpres', 'suara_pilpres.pilpres_id', '=', 'laporan_pilpres.pilpres_id')
            ->join('pilpres', 'suara_pilpres.pilpres_id', '=', 'pilpres.id')
            ->join('users', 'laporan_pilpres.user_id', '=', 'users.id')
            ->join('dapils', 'pilpres.dapil_id', '=', 'dapils.id')
            ->where('pilpres.dapil_id', $this->dapil->id)
            ->get();

        $realCountPresiden = $realCountPresiden->reduce(function ($carry, $item) {
            if (!isset($carry[$item->pilpres_id])) {
                $carry[$item->pilpres_id] = [
                    'email' => $item->email,
                    'name' => $item->name,
                    'index' => $item->index,
                    'kelurahan' => $item->kelurahan,
                    'tps' => $item->tps,
                    'jumlah_dpt' => $item->jumlah_dpt,
                    'hasil_suara_tidak_sah' => $item->hasil_suara_tidak_sah
                ];
            }
            $carry[$item->pilpres_id][$item->capres_id] = $item->jumlah_suara;
            return $carry;
        }, []);

        return collect($realCountPresiden);
    }
}
