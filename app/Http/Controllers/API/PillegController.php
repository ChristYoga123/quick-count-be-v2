<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Caleg;
use App\Models\Dapil;
use App\Models\LaporanPilleg;
use App\Models\Partai;
use App\Models\Pilleg;
use App\Models\SuaraPilleg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PillegController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiAuth');
    }

    public function index(Partai $partai, Dapil $dapil)
    {
        if (!$partai || !$dapil) {
            return ResponseFormatter::error('Data partai tidak ditemukan', 404);
        }
        $calegs = Caleg::where('partai_id', $partai->id)->where('dapil_id', $dapil->id)->get();
        return ResponseFormatter::success($calegs, 'Data caleg berhasil diambil');
    }

    public function submitSuara(Request $request)
    {
        $request->validate([
            'dapil_id' => 'required|exists:dapils,id',
            'kelurahan' => 'required',
            'tps' => 'required',
            'hasil_suara_sah' => 'required|array',
            'hasil_suara_sah.*.caleg_id' => 'required|exists:calegs,id',
            'hasil_suara_sah.*.jumlah_suara' => 'required|integer',
            'hasil_suara_tidak_sah' => 'required',
            'jumlah_dpt' => 'required|integer',
            'laporan' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $pilleg = Pilleg::create([
                'dapil_id' => $request->dapil_id,
                'kelurahan' => $request->kelurahan,
                'tps' => $request->tps,
                'hasil_suara_tidak_sah' => $request->hasil_suara_tidak_sah,
                'jumlah_dpt' => $request->jumlah_dpt
            ]);

            $suaraPilleg = [];
            foreach ($request->hasil_suara_sah as $item) {
                $suaraPilleg[] = [
                    'pilleg_id' => $pilleg->id,
                    'caleg_id' => $item['caleg_id'],
                    'jumlah_suara' => $item['jumlah_suara'],
                ];
            }
            SuaraPilleg::insert($suaraPilleg);
            LaporanPilleg::create([
                'user_id' => Auth::guard('api')->user()->id,
                'pilleg_id' => $pilleg->id,
                'laporan' => $request->laporan
            ]);
            DB::commit();
            return ResponseFormatter::success($pilleg, 'Data pilleg berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
