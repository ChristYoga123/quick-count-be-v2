<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LaporanPilpresDataTable as AdminLaporanPilpresDataTable;
use App\DataTables\LaporanPilpresDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk halaman ini');
        }
    }
}
