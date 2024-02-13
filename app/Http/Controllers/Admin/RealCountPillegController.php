<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RealCountPillegController extends Controller
{
    public function index()
    {
        return view('pages.admin.real-count.pilleg.index')->with([
            'title' => 'Real Count Legislatif',
            'partais' => Partai::all()
        ]);
    }

    public function show(Partai $partai)
    {
        $realCountPilleg = DB::table('suara_pillegs')
            ->select('calegs.nama', DB::raw('SUM(suara_pillegs.jumlah_suara) as jumlah_suara'))
            ->join('calegs', 'suara_pillegs.caleg_id', '=', 'calegs.id')
            ->where('calegs.partai_id', $partai->id)
            ->groupBy('suara_pillegs.caleg_id')
            ->get();
        return response()->json($realCountPilleg);
    }
}
