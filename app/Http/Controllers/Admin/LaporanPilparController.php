<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LaporanPilparDataTable;
use App\Exports\Admin\LaporanPilparExport;
use App\Http\Controllers\Controller;
use App\Models\Dapil;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPilparController extends Controller
{
    public $permission = 'Laporan.web';

    public function index(LaporanPilparDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.laporans.pilpar.index', [
            'title' => 'Laporan Pemilihan Partai'
        ]);
    }

    public function show(Dapil $dapil)
    {
        $this->confirmAuthorization('show');
        $laporanPartai = User::with(['LaporanPilpar', 'LaporanPilpar.Pilpar'])->whereHas('LaporanPilpar', function ($query) use ($dapil) {
            $query->whereHas('Pilpar', function ($query) use ($dapil) {
                $query->where('dapil_id', $dapil->id);
            });
        })->get();

        return view('pages.admin.laporans.pilpar.show', [
            'title' => 'Laporan Pemilihan Partai',
            'laporanPartais' => $laporanPartai
        ]);
    }

    public function export(Dapil $dapil)
    {
        $this->confirmAuthorization('show');
        return Excel::download(new LaporanPilparExport($dapil), 'laporan-pilpar-dapil-' . $dapil->index . '.xlsx');
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk halaman ini');
        }
    }
}
