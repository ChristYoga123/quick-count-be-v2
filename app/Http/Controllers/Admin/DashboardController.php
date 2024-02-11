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
        $capres = Capres::count();
        $partai = Partai::count();
        $caleg = Caleg::count();
        $dapil = Dapil::count();

        $realCountPresiden = DB::table('suara_pilpres')
            ->select('capres.nama_paslon', DB::raw('SUM(suara_pilpres.jumlah_suara) as jumlah_suara'))
            ->join('capres', 'suara_pilpres.capres_id', '=', 'capres.id')
            ->groupBy('suara_pilpres.capres_id')
            ->get();

        $realCountPartai = DB::table('suara_pilpars')
            ->select('partais.nama', DB::raw('SUM(suara_pilpars.jumlah_suara) as jumlah_suara'))
            ->join('partais', 'suara_pilpars.partai_id', '=', 'partais.id')
            ->groupBy('suara_pilpars.partai_id')
            ->get();

        return view('pages.admin.dashboard.index')->with([
            'title' => 'Dashboard',
            'capres' => $capres,
            'partai' => $partai,
            'caleg' => $caleg,
            'dapil' => $dapil,
            'realCountPresiden' => $realCountPresiden,
            'realCountPartai' => $realCountPartai,
        ]);
    }
}
