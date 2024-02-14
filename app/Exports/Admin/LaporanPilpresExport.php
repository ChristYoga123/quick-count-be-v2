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
        $realCountPresiden = DB::table('laporan_pilpres')
            ->select('users.email', 'users.name', 'dapils.index', 'pilpres.kelurahan', 'pilpres.tps', 'pilpres.jumlah_dpt', 'pilpres.hasil_suara_tidak_sah')
            ->join('users', 'laporan_pilpres.user_id', '=', 'users.id')
            ->join('pilpres', 'laporan_pilpres.pilpres_id', '=', 'pilpres.id')
            ->join('dapils', 'pilpres.dapil_id', '=', 'dapils.id')
            ->where('pilpres.dapil_id', $this->dapil->id)
            ->get();

        $suaraSah = DB::table('suara_pilpres')
            ->select('capres.no_urut_paslon', 'suara_pilpres.jumlah_suara', 'pilpres.kelurahan', 'pilpres.tps')
            ->join('capres', 'suara_pilpres.capres_id', '=', 'capres.id')
            ->join('pilpres', 'suara_pilpres.pilpres_id', '=', 'pilpres.id')
            ->where('pilpres.dapil_id', $this->dapil->id)
            ->get();

        foreach ($realCountPresiden as $key => $value) {
            $suaraSahNoUrut1 = 0;
            $suaraSahNoUrut2 = 0;
            $suaraSahNoUrut3 = 0;
            foreach ($suaraSah as $key2 => $value2) {
                if ($value->kelurahan == $value2->kelurahan && $value->tps == $value2->tps) {
                    if ($value2->no_urut_paslon == 1) {
                        $suaraSahNoUrut1 = $value2->jumlah_suara;
                    } elseif ($value2->no_urut_paslon == 2) {
                        $suaraSahNoUrut2 = $value2->jumlah_suara;
                    } elseif ($value2->no_urut_paslon == 3) {
                        $suaraSahNoUrut3 = $value2->jumlah_suara;
                    }
                }
            }
            $realCountPresiden[$key]->suara_sah_no_urut_1 = $suaraSahNoUrut1;
            $realCountPresiden[$key]->suara_sah_no_urut_2 = $suaraSahNoUrut2;
            $realCountPresiden[$key]->suara_sah_no_urut_3 = $suaraSahNoUrut3;
        }

        return $realCountPresiden;
    }
}
