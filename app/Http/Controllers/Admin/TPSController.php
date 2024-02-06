<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TPSDataTable;
use App\Http\Controllers\Controller;
use App\Models\TPS;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TPSController extends Controller
{
    public $permission = "TPS.web";
    /**
     * Display a listing of the resource.
     */
    public function index(TPSDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render(
            'pages.admin.master.tps.index',

            [
                'title' => 'TPS Mojokerto',
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization('create');
        $cityId = DB::table('cities')->where('city_name', 'MOJOKERTO')->first()->city_id;
        return view('pages.admin.master.tps.create', [
            'title' => 'Tambah TPS Mojokerto',
            'kecamatans' => DB::table('districts')->where('city_id', $cityId)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'index' => 'required',
            'kecamatan' => 'required|exists:districts,dis_name',
            'kelurahan' => 'required|exists:subdistricts,subdis_name',
        ]);
        DB::beginTransaction();
        try {
            TPS::create([
                'index' => $request->index,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
            ]);
            DB::commit();
            return redirect()->route('admin.master.tps.index')->with('success', 'TPS berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.tps.create')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TPS $tp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TPS $tp)
    {
        $this->confirmAuthorization('edit');
        $cityId = DB::table('cities')->where('city_name', 'MOJOKERTO')->first()->city_id;
        return view('pages.admin.master.tps.edit', [
            'title' => 'Edit TPS Mojokerto',
            'kecamatans' => DB::table('districts')->where('city_id', $cityId)->get(),
            'kelurahans' => DB::table('subdistricts')->where('dis_id', DB::table('districts')->where('dis_name', $tp->kecamatan)->first()->dis_id)->get(),
            'tps' => $tp
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TPS $tp)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            'index' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $tp->update([
                'index' => $request->index,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
            ]);
            DB::commit();
            return redirect()->route('admin.master.tps.index')->with('success', 'TPS berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.tps.edit', $tp->id)->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TPS $tp)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            $tp->delete();
            DB::commit();
            return redirect()->route('admin.master.tps.index')->with('success', 'TPS berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.tps.index')->with('error', $e->getMessage());
        }
    }

    public function getDistricts()
    {
        $id = DB::table('cities')->where('city_name', 'MOJOKERTO')->first()->city_id;
        $districts = DB::table('districts')->where('city_id', $id)->get();
        return response()->json($districts);
    }

    public function getSubDistricts($districtName)
    {
        $id = DB::table('districts')->where('dis_name', $districtName)->first()->dis_id;
        $subDistricts = DB::table('subdistricts')->where('dis_id', $id)->get();
        return response()->json($subDistricts);
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk mengakses halaman ini');
        }
    }
}
