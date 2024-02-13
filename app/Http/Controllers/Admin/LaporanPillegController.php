<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LaporanPillegDataTable;
use App\Exports\Admin\LaporanPillegExport;
use App\Http\Controllers\Controller;
use App\Models\Dapil;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPillegController extends Controller
{
    public $permission = 'Laporan.web';

    public function index(LaporanPillegDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.laporans.pilleg.index', [
            'title' => 'Laporan Pemilihan Legislatif'
        ]);
    }

    public function show(Dapil $dapil)
    {
        $this->confirmAuthorization('show');
        $laporanPilleg = User::with(['LaporanPilleg', 'LaporanPilleg.Pilleg'])->whereHas('LaporanPilleg', function ($query) use ($dapil) {
            $query->whereHas('Pilleg', function ($query) use ($dapil) {
                $query->where('dapil_id', $dapil->id);
            });
        })->get();

        return view('pages.admin.laporans.pilleg.show', [
            'title' => 'Laporan Pemilihan Legislatif',
            'laporanPillegs' => $laporanPilleg
        ]);
    }

    public function export(Dapil $dapil)
    {
        $this->confirmAuthorization('show');
        return Excel::download(new LaporanPillegExport($dapil), 'laporan-legislatif-dapil-' . $dapil->index . '.xlsx');
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk halaman ini');
        }
    }
}
