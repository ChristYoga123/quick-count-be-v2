<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dapil;
use App\Models\SuaraPilpres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class RealCountPresidenController extends Controller
{
    public function index(Request $request)
    {
        $realCountPresiden = DB::table('suara_pilpres')
            ->select('capres.nama_paslon', DB::raw('SUM(suara_pilpres.jumlah_suara) as jumlah_suara'))
            ->join('capres', 'suara_pilpres.capres_id', '=', 'capres.id')
            ->groupBy('suara_pilpres.capres_id')
            ->get();
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

        return response()->json($realCountPresiden);
    }
}
