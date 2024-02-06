<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CapresDataTable;
use App\Http\Controllers\Controller;
use App\Models\Capres;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CapresController extends Controller
{
    public $permission = "Capres.web";
    /**
     * Display a listing of the resource.
     */
    public function index(CapresDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.master.capres.index', [
            'title' => 'Capres',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization('create');
        return view('pages.admin.master.capres.create', [
            'title' => 'Tambah Capres',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'no_urut_paslon' => 'required|numeric|unique:capres,no_urut_paslon',
            'nama_paslon' => 'required|string|max:255',
        ]);
        DB::beginTransaction();
        try {
            Capres::create($request->all());
            DB::commit();
            return redirect()->route('admin.master.capres.index')->with('success', 'Capres berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.capres.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Capres $capre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Capres $capre)
    {
        $this->confirmAuthorization('edit');
        return view('pages.admin.master.capres.edit', [
            'title' => 'Edit Capres',
            'capres' => $capre,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Capres $capre)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            'no_urut_paslon' => 'required|numeric|unique:capres,no_urut_paslon,' . $capre->id,
            'nama_paslon' => 'required|string|max:255',
        ]);
        DB::beginTransaction();
        try {
            $capre->update($request->all());
            DB::commit();
            return redirect()->route('admin.master.capres.index')->with('success', 'Capres berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.capres.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Capres $capre)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            $capre->delete();
            DB::commit();
            return redirect()->route('admin.master.capres.index')->with('success', 'Capres berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.capres.index')->with('error', $e->getMessage());
        }
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk halaman ini.');
        }
    }
}
