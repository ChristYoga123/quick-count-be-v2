<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PartaiDataTable;
use App\Http\Controllers\Controller;
use App\Models\Partai;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartaiController extends Controller
{
    public $permission = 'Partai.web';
    /**
     * Display a listing of the resource.
     */
    public function index(PartaiDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.master.partai.index', [
            'title' => 'Partai'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization('create');
        return view('pages.admin.master.partai.create', [
            'title' => 'Tambah Partai'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'nama' => 'required|unique:partais,nama',
            'fotoPartai' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $partai = Partai::create([
                'nama' => ucwords($request->nama),
            ]);

            $partai->addMediaFromRequest('fotoPartai')->toMediaCollection('partai');
            DB::commit();
            return redirect()->route('admin.master.partai.index')->with('success', 'Partai berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.partai.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Partai $partai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partai $partai)
    {
        $this->confirmAuthorization('edit');
        return view('pages.admin.master.partai.edit', [
            'title' => 'Edit Partai',
            'partai' => $partai
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partai $partai)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            'nama' => 'required|unique:partais,nama,' . $partai->id,
            'fotoPartai' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        DB::beginTransaction();
        try {
            $partai->update([
                'nama' => ucwords($request->nama),
            ]);

            if ($request->hasFile('fotoPartai')) {
                $partai->clearMediaCollection('partai');
                $partai->addMediaFromRequest('fotoPartai')->toMediaCollection('partai');
            }
            DB::commit();
            return redirect()->route('admin.master.partai.index')->with('success', 'Partai berhasil diubah');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.partai.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partai $partai)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            $partai->clearMediaCollection('partai');
            $partai->delete();
            DB::commit();
            return redirect()->route('admin.master.partai.index')->with('success', 'Partai berhasil dihapus');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.partai.index')->with('error', $e->getMessage());
        }
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException("Anda tidak memiliki akses untuk halaman ini.");
        }
    }
}
