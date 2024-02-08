<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CalegDataTable;
use App\Http\Controllers\Controller;
use App\Imports\Admin\CalegImport;
use App\Models\Caleg;
use App\Models\Dapil;
use App\Models\Partai;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CalegController extends Controller
{
    public $permission = "Caleg.web";
    /**
     * Display a listing of the resource.
     */
    public function index(CalegDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.master.caleg.index', [
            'title' => 'Caleg DPRD Mojokerto',
            'partais' => Partai::all(),
            'dapils' => Dapil::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization('create');
        return view('pages.admin.master.caleg.create', [
            'title' => 'Tambah Caleg',
            'partais' => Partai::all(),
            'dapils' => Dapil::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls'
        ]);
        DB::beginTransaction();
        try {
            Excel::import(new CalegImport, $request->file('excelFile'));
            DB::commit();
            return redirect()->route('admin.master.caleg.index')->with('success', 'Caleg berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.caleg.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Caleg $caleg)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Caleg $caleg)
    {
        $this->confirmAuthorization('edit');
        return view('pages.admin.master.caleg.edit', [
            'title' => 'Edit Caleg',
            'caleg' => $caleg,
            'partais' => Partai::all(),
            'dapils' => Dapil::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caleg $caleg)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            'no_urut' => 'required|numeric',
            'nama' => 'required|unique:calegs,nama,' . $caleg->id,
            'partai' => 'required|exists:partais,id',
            'dapil' => 'required|exists:dapils,id',
        ]);
        DB::beginTransaction();
        try {
            $caleg->update([
                'no_urut' => $request->no_urut,
                'nama' => $request->nama,
                'partai_id' => $request->partai,
                'dapil_id' => $request->dapil,
            ]);
            DB::commit();
            return redirect()->route('admin.master.caleg.index')->with('success', 'Caleg berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caleg $caleg)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            $caleg->delete();
            DB::commit();
            return redirect()->route('admin.master.caleg.index')->with('success', 'Caleg berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk halaman ini.');
        }
    }
}
