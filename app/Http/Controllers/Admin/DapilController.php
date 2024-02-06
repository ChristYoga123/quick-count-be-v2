<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\DapilDataTable;
use App\Http\Controllers\Controller;
use App\Models\Dapil;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DapilController extends Controller
{
    public $permission = "Dapil.web";
    /**
     * Display a listing of the resource.
     */
    public function index(DapilDataTable $dataTable)
    {
        $this->confirmAuthorization("index");
        return $dataTable->render('pages.admin.master.dapil.index', [
            'title' => 'Dapil',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization("create");
        $magerSari = DB::table('districts')->where('dis_name', 'MAGERSARI')->first()->dis_id;
        $kranggan = DB::table('districts')->where('dis_name', 'KRANGGAN')->first()->dis_id;
        $prajuritKulon = DB::table('districts')->where('dis_name', 'PRAJURITKULON')->first()->dis_id;
        return view('pages.admin.master.dapil.create', [
            'title' => 'Tambah Dapil',
            'kecamatans' => DB::table('subdistricts')->whereIn('dis_id', [$magerSari, $kranggan, $prajuritKulon])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'index' => 'required|unique:dapils,index',
            'kecamatan' => 'required|array',
        ]);
        DB::beginTransaction();
        try {
            $dapil = Dapil::create([
                'index' => $request->index,
                'kecamatan' => json_encode($request->kecamatan)
            ]);
            DB::commit();
            return redirect()->route('admin.master.dapil.index')->with('success', 'Dapil berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Dapil $dapil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dapil $dapil)
    {
        $this->confirmAuthorization('edit');
        $magerSari = DB::table('districts')->where('dis_name', 'MAGERSARI')->first()->dis_id;
        $kranggan = DB::table('districts')->where('dis_name', 'KRANGGAN')->first()->dis_id;
        $prajuritKulon = DB::table('districts')->where('dis_name', 'PRAJURITKULON')->first()->dis_id;
        return view('pages.admin.master.dapil.edit', [
            'title' => 'Edit Dapil',
            'kecamatans' => DB::table('subdistricts')->whereIn('dis_id', [$magerSari, $kranggan, $prajuritKulon])->get(),
            'dapil' => $dapil
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dapil $dapil)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            'index' => 'required|unique:dapils,index,' . $dapil->id,
            'kecamatan' => 'array',
        ]);
        DB::beginTransaction();
        try {
            if (!$request->kecamatan) {
                $dapil->update([
                    'index' => $request->index,
                ]);
            } else {
                $dapil->update([
                    'index' => $request->index,
                    'kecamatan' => json_encode($request->kecamatan)
                ]);
            }
            DB::commit();
            return redirect()->route('admin.master.dapil.index')->with('success', 'Dapil berhasil diubah');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dapil $dapil)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            $dapil->delete();
            DB::commit();
            return redirect()->route('admin.master.dapil.index')->with('success', 'Dapil berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . "." . $operation)) {
            throw new AuthorizationException("Anda tidak memiliki akses ke halaman ini");
        }
    }
}
