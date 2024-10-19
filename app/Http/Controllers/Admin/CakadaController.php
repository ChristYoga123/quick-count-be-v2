<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cakada;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CakadaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cakada = Cakada::all();
            return datatables()->of($cakada)
                ->addColumn('action', function ($cakada) {
                    $button = '<a href="' . route('admin.master.cakada.edit', $cakada->id) . '" class="btn btn-sm btn-primary">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" onclick="deleteCakada(' . $cakada->id . ')" class="delete btn btn-sm btn-danger">Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.admin.master.cakada.index', [
            'title' => 'Calon Kepala Daerah',
        ]);
    }

    public function create()
    {
        return view('pages.admin.master.cakada.create', [
            'title' => 'Tambah Calon Kepala Daerah',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_urut_paslon' => 'required|numeric',
            'nama_paslon' => 'required',
        ]);
        DB::beginTransaction();
        try {
            Cakada::create($request->all());
            DB::commit();
            return redirect()->route('admin.master.cakada.index')->with('success', 'Data berhasil disimpan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $cakada = Cakada::findOrFail($id);
        return view('pages.admin.master.cakada.edit', [
            'title' => 'Edit Calon Kepala Daerah',
            'cakada' => $cakada,
        ]);
    }

    public function update(Request $request, Cakada $cakada)
    {
        $request->validate([
            'no_urut_paslon' => 'required|numeric',
            'nama_paslon' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $cakada->update($request->all());
            DB::commit();
            return redirect()->route('admin.master.cakada.index')->with('success', 'Data berhasil diupdate');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Cakada::findOrFail($id)->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
