<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dapil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RealCountPartaiController extends Controller
{
    public function index(Request $request)
    {
        $realCountPartai = DB::table('suara_pilpars')
            ->select('partais.nama', DB::raw('SUM(suara_pilpars.jumlah_suara) as jumlah_suara'))
            ->join('partais', 'suara_pilpars.partai_id', '=', 'partais.id')
            ->groupBy('suara_pilpars.partai_id')
            ->get();
        $suaraTidakSah = DB::table('pilpars')
            ->select(DB::raw('SUM(hasil_suara_tidak_sah) as suara_tidak_sah'))
            ->first();
        $suaraTidahSahData = [
            'nama' => 'Tidak Sah',
            'jumlah_suara' => $suaraTidakSah->suara_tidak_sah,
            'color' => 'gray'
        ];
        $colorPartai = [
            '#017042', '#EC1F25', '#DB1F26', '#FACD0A', '#1E1C58', '#F68B54', '#0FBDEE', '#EF4323', '#ED1C24', '#FFCC08', 'blue', '#005CA8', '#0F6741', '#A0D0F0', '#E94031', '#233775', '#436F42', '#040708'
        ];
        foreach ($realCountPartai as $key => $value) {
            $value->color = $colorPartai[$key];
        }
        $realCountPartai->push($suaraTidahSahData);
        return view('pages.admin.real-count.pilpar.index')->with([
            'title' => 'Real Count Pemilihan Partai',
            'dapils' => Dapil::all(),
            'data' => $realCountPartai,
        ]);
    }

    public function show(Dapil $dapil)
    {
        $realCountPartai = DB::table('suara_pilpars')
            ->select('partais.nama', DB::raw('SUM(suara_pilpars.jumlah_suara) as jumlah_suara'))
            ->join('partais', 'suara_pilpars.partai_id', '=', 'partais.id')
            ->join('pilpars', 'suara_pilpars.pilpar_id', '=', 'pilpars.id')
            ->where('pilpars.dapil_id', $dapil->id)
            ->groupBy('suara_pilpars.partai_id')
            ->get();
        $suaraTidakSah = DB::table('pilpars')
            ->select(DB::raw('SUM(hasil_suara_tidak_sah) as suara_tidak_sah'))
            ->where('pilpars.dapil_id', $dapil->id)
            ->join('dapils', 'pilpars.dapil_id', '=', 'dapils.id')
            ->first();
        $suaraTidahSahData = [
            'nama' => 'Tidak Sah',
            'jumlah_suara' => $suaraTidakSah->suara_tidak_sah,
            'color' => 'gray'
        ];
        $colorPartai = [
            '#017042', '#EC1F25', '#DB1F26', '#FACD0A', '#1E1C58', '#F68B54', '#0FBDEE', '#EF4323', '#ED1C24', '#FFCC08', 'blue', '#005CA8', '#0F6741', '#A0D0F0', '#E94031', '#233775', '#436F42', '#040708'
        ];
        foreach ($realCountPartai as $key => $value) {
            $value->color = $colorPartai[$key];
        }
        $realCountPartai->push($suaraTidahSahData);
        return response()->json($realCountPartai);
    }
}
