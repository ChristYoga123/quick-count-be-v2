<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\RealCountPilkadaExport;
use App\Exports\Admin\RealCountPresidenExport;
use App\Http\Controllers\Controller;
use App\Models\Dapil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RealCountPilkadaController extends Controller
{
    public function index(Request $request)
    {
        $realCountPilkada = DB::table('suara_pilkadas')
            ->select('cakadas.nama_paslon', DB::raw('SUM(suara_pilkadas.jumlah_suara) as jumlah_suara'))
            ->join('cakadas', 'suara_pilkadas.cakada_id', '=', 'cakadas.id')
            ->groupBy('suara_pilkadas.cakada_id')
            ->get();

        $suaraTidakSah = DB::table('pilkadas')
            ->select(DB::raw('SUM(pilkadas.hasil_suara_tidak_sah) as suara_tidak_sah'))
            ->first();

        $suaraTidakSahData = [
            'nama_paslon' => 'Tidak Sah',
            'jumlah_suara' => $suaraTidakSah->suara_tidak_sah,
            'color' => 'gray'
        ];

        $color = ['#00cc00', '#6699ff'];
        foreach ($realCountPilkada as $key => $value) {
            $value->color = $color[$key];
        }

        $realCountPilkada->push($suaraTidakSahData);

        return view('pages.admin.real-count.pilkada.index')->with([
            'title' => 'Real Count Pemilihan Kepala Daerah',
            'dapils' => Dapil::all(),
            'data' => $realCountPilkada,
        ]);
    }

    public function show(Dapil $dapil)
    {
        $realCountPilkada = DB::table('suara_pilkadas')
            ->select('cakadas.nama_paslon', DB::raw('SUM(suara_pilkadas.jumlah_suara) as jumlah_suara'))
            ->join('cakadas', 'suara_pilkadas.cakada_id', '=', 'cakadas.id')
            ->join('pilkadas', 'suara_pilkadas.pilkada_id', '=', 'pilkadas.id')
            ->where('pilkadas.dapil_id', $dapil->id)
            ->groupBy('suara_pilkadas.cakada_id')
            ->get();

        $suaraTidakSah = DB::table('pilkadas')
            ->select(DB::raw('SUM(pilkadas.hasil_suara_tidak_sah) as suara_tidak_sah'))
            ->where('pilkadas.dapil_id', $dapil->id)
            ->join('dapils', 'pilkadas.dapil_id', '=', 'dapils.id')
            ->first();

        $suaraTidakSahData = [
            'nama_paslon' => 'Tidak Sah',
            'jumlah_suara' => $suaraTidakSah->suara_tidak_sah,
            'color' => 'gray'
        ];

        $color = ['#00cc00', '#6699ff'];
        foreach ($realCountPilkada as $key => $value) {
            $value->color = $color[$key];
        }

        $realCountPilkada->push($suaraTidakSahData);
        return response()->json($realCountPilkada);
    }

    public function export()
    {
        return Excel::download(new RealCountPilkadaExport(), 'real-count-pilkada.xlsx');
    }
}
