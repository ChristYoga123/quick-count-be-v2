<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LaporanPilpresDataTable as AdminLaporanPilpresDataTable;
use App\DataTables\LaporanPilpresDataTable;
use App\Http\Controllers\Controller;
use App\Models\Dapil;
use App\Models\LaporanPilpres;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanPilpresController extends Controller
{
    public $permission = 'Laporan.web';

    public function index(AdminLaporanPilpresDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.laporans.pilpres.index', [
            'title' => 'Laporan Pemilihan Presiden'
        ]);
    }

    public function show(Dapil $dapil)
    {
        /*
            buatkan saya strukdat laporan pilpres berdasarkan dapil_id dengan format berikut
            laporan:

            nama: Admin
            laporan: [
                {
                    'kelurahan': 'Kelurahan 1',
                    'tps': 'TPS 1',
                    'laporan': 'Laporan 1'
                },
                {
                    'kelurahan': 'Kelurahan 2',
                    'tps': 'TPS 2',
                    'laporan': 'Laporan 2'
                }
            ]
        */
        $laporanPilpres = User::with(['LaporanPilpres', 'LaporanPilpres.Pilpres'])->whereHas('LaporanPilpres', function ($query) use ($dapil) {
            $query->whereHas('Pilpres', function ($query) use ($dapil) {
                $query->where('dapil_id', $dapil->id);
            });
        })->get();


        $this->confirmAuthorization('show');
        return view('pages.admin.laporans.pilpres.show', [
            'title' => 'Detail Laporan Pemilihan Presiden Dapil ' . $dapil->index,
            'laporanPilpres' => $laporanPilpres
        ]);
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk halaman ini');
        }
    }
}
