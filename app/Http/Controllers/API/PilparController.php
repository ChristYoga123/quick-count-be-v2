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
        $this->middleware('apiAuth');
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
            'jumlah_dpt' => 'required|integer',
            'laporan' => 'required|string'
        ], [
            'dapil_id.required' => 'Dapil tidak boleh kosong',
            'dapil_id.exists' => 'Dapil tidak ditemukan',
            'kelurahan.required' => 'Kelurahan tidak boleh kosong',
            'tps.required' => 'TPS tidak boleh kosong',
            'hasil_suara_sah.required' => 'Hasil suara sah tidak boleh kosong',
            'hasil_suara_sah.array' => 'Hasil suara sah harus berupa array',
            'hasil_suara_sah.partai_id.integer' => 'Partai id harus berupa angka',
            'hasil_suara_sah.partai_id.exists' => 'Partai id tidak ditemukan',
            'hasil_suara_sah.jumlah_suara.integer' => 'Jumlah suara harus berupa angka',
            'hasil_suara_tidak_sah.required' => 'Hasil suara tidak sah tidak boleh kosong',
            'jumlah_dpt.required' => 'Jumlah DPT tidak boleh kosong',
            'jumlah_dpt.integer' => 'Jumlah DPT harus berupa angka',
            'laporan.required' => 'Laporan tidak boleh kosong',
            'laporan.string' => 'Laporan harus berupa string'
        ]);

        DB::beginTransaction();
        try {
            $pilpar = Pilpar::create([
                'dapil_id' => $request->dapil_id,
                'kelurahan' => $request->kelurahan,
                'tps' => $request->tps,
                'hasil_suara_tidak_sah' => $request->hasil_suara_tidak_sah,
                'jumlah_dpt' => $request->jumlah_dpt
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
