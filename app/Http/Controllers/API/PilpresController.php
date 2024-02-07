<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Capres;
use App\Models\LaporanPilpres;
use App\Models\Pilpres;
use App\Models\SuaraPilpres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PilpresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    public function index()
    {
        $capres = Capres::all();
        return ResponseFormatter::success($capres, 'Data Capres berhasil diambil');
    }

    public function submitSuara(Request $request)
    {
        $request->validate([
            'dapil_id' => 'required|exists:dapils,id',
            'kelurahan' => 'required',
            'tps' => 'required',
            'hasil_suara_sah' => 'required|array',
            'hasil_suara_sah.capres_id' => 'integer|exists:capres,id',
            'hasil_suara_sah.jumlah_suara' => 'integer',
            'hasil_suara_tidak_sah' => 'required|integer',
            'jumlah_dpt' => 'required|integer',
            'laporan' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $pilpres = Pilpres::create([
                'dapil_id' => $request->dapil_id,
                'kelurahan' => $request->kelurahan,
                'tps' => $request->tps,
                'hasil_suara_tidak_sah' => $request->hasil_suara_tidak_sah,
                'jumlah_dpt' => $request->jumlah_dpt
            ]);

            // gunakan bulk insert
            $suaraPilpres = [];
            foreach ($request->hasil_suara_sah as $suara) {
                $suaraPilpres[] = [
                    'pilpres_id' => $pilpres->id,
                    'capres_id' => $suara['capres_id'],
                    'jumlah_suara' => $suara['jumlah_suara']
                ];
            }
            SuaraPilpres::insert($suaraPilpres);
            LaporanPilpres::create([
                'user_id' => Auth::guard('api')->user()->id,
                'pilpres_id' => $pilpres->id,
                'laporan' => $request->laporan
            ]);
            DB::commit();
            return ResponseFormatter::success($pilpres, 'Data Pilpres berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), $e->getCode());
        }
    }
}
