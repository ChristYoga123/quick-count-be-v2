<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LaporanPilpar;
use App\Models\Partai;
use App\Models\Pilpar;
use App\Models\SuaraPilpar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PilparController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    public function index()
    {
        $partai = Partai::with('media')->get();
        return ResponseFormatter::success($partai, 'Data Partai berhasil diambil');
    }

    public function submitSuara(Request $request)
    {
        $request->validate([
            'dapil_id' => 'required|exists:dapils,id',
            'kelurahan' => 'required',
            'tps' => 'required',
            'hasil_suara_sah' => 'required|array',
            'hasil_suara_sah.partai_id' => 'integer|exists:partai,id',
            'hasil_suara_sah.jumlah_suara' => 'integer',
            'hasil_suara_tidak_sah' => 'required|integer',
            'laporan' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $pilpar = Pilpar::create([
                'dapil_id' => $request->dapil_id,
                'kelurahan' => $request->kelurahan,
                'tps' => $request->tps,
                'hasil_suara_tidak_sah' => $request->hasil_suara_tidak_sah
            ]);

            // gunakan bulk insert
            $suaraPilpar = [];
            foreach ($request->hasil_suara_sah as $suara) {
                $suaraPilpar[] = [
                    'pilpar_id' => $pilpar->id,
                    'partai_id' => $suara['partai_id'],
                    'jumlah_suara' => $suara['jumlah_suara']
                ];
            }
            SuaraPilpar::insert($suaraPilpar);
            LaporanPilpar::create([
                'user_id' => Auth::guard('api')->user()->id,
                'pilpar_id' => $pilpar->id,
                'laporan' => $request->laporan
            ]);
            DB::commit();
            return ResponseFormatter::success($pilpar, 'Data Pilpar berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), $e->getCode());
        }
    }
}
