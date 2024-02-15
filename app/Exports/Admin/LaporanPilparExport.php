<?php

namespace App\Exports\Admin;

use App\Models\Dapil;
use App\Models\Partai;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPilparExport implements FromCollection
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
        $realCountPartai = DB::table('suara_pilpars')
            ->select('users.email', 'users.name', 'dapils.index', 'pilpars.kelurahan', 'pilpars.tps', 'pilpars.jumlah_dpt', 'pilpars.hasil_suara_tidak_sah', 'suara_pilpars.partai_id', 'suara_pilpars.jumlah_suara', 'pilpars.id as pilpar_id')
            ->join('laporan_pilpars', 'suara_pilpars.pilpar_id', '=', 'laporan_pilpars.pilpar_id')
            ->join('pilpars', 'suara_pilpars.pilpar_id', '=', 'pilpars.id')
            ->join('users', 'laporan_pilpars.user_id', '=', 'users.id')
            ->join('dapils', 'pilpars.dapil_id', '=', 'dapils.id')
            ->where('pilpars.dapil_id', $this->dapil->id)
            ->get();

        $realCountPartai = $realCountPartai->reduce(function ($carry, $item) {
            if (!isset($carry[$item->pilpar_id])) {
                $carry[$item->pilpar_id] = [
                    'email' => $item->email,
                    'name' => $item->name,
                    'index' => $item->index,
                    'kelurahan' => $item->kelurahan,
                    'tps' => $item->tps,
                    'jumlah_dpt' => $item->jumlah_dpt,
                    'hasil_suara_tidak_sah' => $item->hasil_suara_tidak_sah
                ];
            }
            $carry[$item->pilpar_id][$item->partai_id] = $item->jumlah_suara;
            return $carry;
        }, []);

        return collect($realCountPartai);
    }
}
