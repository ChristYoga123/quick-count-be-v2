<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\RealCountPresidenExport;
use App\Http\Controllers\Controller;
use App\Models\Dapil;
use App\Models\SuaraPilpres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RealCountPresidenController extends Controller
{
    public function index(Request $request)
    {
        $realCountPresiden = DB::table('suara_pilpres')
            ->select('capres.nama_paslon', DB::raw('SUM(suara_pilpres.jumlah_suara) as jumlah_suara'))
            ->join('capres', 'suara_pilpres.capres_id', '=', 'capres.id')
            ->groupBy('suara_pilpres.capres_id')
            ->get();

        $suaraTidakSah = DB::table('pilpres')
            ->select(DB::raw('SUM(pilpres.hasil_suara_tidak_sah) as suara_tidak_sah'))
            ->first();
        $suaraTidakSahData = [
            'nama_paslon' => 'Tidak Sah',
            'jumlah_suara' => $suaraTidakSah->suara_tidak_sah,
            'color' => 'gray'
        ];
        $color = ['#00cc00', '#6699ff', 'red'];
        foreach ($realCountPresiden as $key => $value) {
            $value->color = $color[$key];
        }
        $realCountPresiden->push($suaraTidakSahData);
        return view('pages.admin.real-count.pilpres.index')->with([
            'title' => 'Real Count Pemilihan Presiden',
            'dapils' => Dapil::all(),
            'data' => $realCountPresiden,
        ]);
    }

    public function show(Dapil $dapil)
    {
        $realCountPresiden = DB::table('suara_pilpres')
            ->select('capres.nama_paslon', DB::raw('SUM(suara_pilpres.jumlah_suara) as jumlah_suara'))
            ->join('capres', 'suara_pilpres.capres_id', '=', 'capres.id')
            ->join('pilpres', 'suara_pilpres.pilpres_id', '=', 'pilpres.id')
            ->where('pilpres.dapil_id', $dapil->id)
            ->groupBy('suara_pilpres.capres_id')
            ->get();

        $suaraTidakSah = DB::table('pilpres')
            ->select(DB::raw('SUM(pilpres.hasil_suara_tidak_sah) as suara_tidak_sah'))
            ->where('pilpres.dapil_id', $dapil->id)
            ->join('dapils', 'pilpres.dapil_id', '=', 'dapils.id')
            ->first();

        $suaraTidakSahData = [
            'nama_paslon' => 'Tidak Sah',
            'jumlah_suara' => $suaraTidakSah->suara_tidak_sah,
            'color' => 'gray'
        ];

        $color = ['#00cc00', '#6699ff', 'red'];
        foreach ($realCountPresiden as $key => $value) {
            $value->color = $color[$key];
        }
        $realCountPresiden->push($suaraTidakSahData);
        return response()->json($realCountPresiden);
    }

    public function export()
    {
        return Excel::download(new RealCountPresidenExport(), 'real-count-pilpres.xlsx');
    }
}
