<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caleg;
use App\Models\Capres;
use App\Models\Dapil;
use App\Models\Partai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /* Card Data */
        $capres = Capres::count();
        $partai = Partai::count();
        $caleg = Caleg::count();
        $dapil = Dapil::count();

        /* Chart Data */
        // Presiden
        $realCountPresiden = DB::table('suara_pilpres')
            ->select('capres.nama_paslon', DB::raw('SUM(suara_pilpres.jumlah_suara) as total'))
            ->join('capres', 'capres.id', '=', 'suara_pilpres.capres_id')
            ->groupBy('suara_pilpres.capres_id')
            ->get();
        $color = ['#00cc00', '#6699ff', 'red'];
        foreach ($realCountPresiden as $key => $value) {
            $value->color = $color[$key];
        }

        $suaraSah = DB::table('suara_pilpres')->sum('jumlah_suara');
        $suaraTidakSah = DB::table('pilpres')->sum('hasil_suara_tidak_sah');
        $total = $suaraSah + $suaraTidakSah;

        foreach ($realCountPresiden as $key => $value) {
            $value->persen = (string) (round(($value->total / $total) * 100, 2));
        }

        $suaraTidakSahPresiden = [
            'nama_paslon' => 'Tidak Sah',
            'total' => $suaraTidakSah,
            'color' => 'gray',
            'persen' => (string) (round(($suaraTidakSah / $total) * 100, 2)),
        ];

        $realCountPresiden->push($suaraTidakSahPresiden);

        // Partai
        $realCountPartai = DB::table('suara_pilpars')
            ->select('partais.nama', DB::raw('SUM(suara_pilpars.jumlah_suara) as total'))
            ->join('partais', 'suara_pilpars.partai_id', '=', 'partais.id')
            ->groupBy('suara_pilpars.partai_id')
            ->get();
        $colorPartai = [
            '#017042', '#EC1F25', '#DB1F26', '#FACD0A', '#1E1C58', '#F68B54', '#0FBDEE', '#EF4323', '#ED1C24', '#FFCC08', 'blue', '#005CA8', '#0F6741', '#A0D0F0', '#E94031', '#233775', '#436F42', '#040708'
        ];
        foreach ($realCountPartai as $key => $value) {
            $value->color = $colorPartai[$key];
        }

        $suaraTidakSahPartai = DB::table('pilpars')->sum('hasil_suara_tidak_sah');
        $suaraTidakSahPartai = [
            'nama' => 'Tidak Sah',
            'total' => $suaraTidakSahPartai,
            'color' => 'gray'
        ];
        $realCountPartai->push($suaraTidakSahPartai);

        return view('pages.admin.dashboard.index', [
            'title' => 'Dashboard',
            'caleg' => $caleg,
            'partai' => $partai,
            'capres' => $capres,
            'dapil' => $dapil,
            'realCountPresiden' => $realCountPresiden,
            'realCountPartai' => $realCountPartai,
        ]);
    }
}
