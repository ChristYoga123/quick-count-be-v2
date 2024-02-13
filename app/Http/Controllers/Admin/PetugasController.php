<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PetugasDataTable;
use App\Exports\Admin\PetugasExport;
use App\Http\Controllers\Controller;
use App\Imports\Admin\PetugasImport;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCredential;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PetugasController extends Controller
{
    public $permission = "Petugas.web";
    /**
     * Display a listing of the resource.
     */
    public function index(PetugasDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.master.users.index', [
            'title' => 'Daftar Petugas',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization('create');
        return view('pages.admin.master.users.create', [
            'title' => 'Tambah Petugas',
            'roles' => Role::all()
        ]);
    }

    public function singleStore(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone_number' => 'required|numeric'
        ]);
        DB::beginTransaction();
        try {
            $petuga = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $petuga->assignRole($request->roles);
            UserCredential::create([
                'user_id' => $petuga->id,
                'phone_number' => $request->phone_number
            ]);
            DB::commit();
            return redirect()->route('admin.master.petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.petugas.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx'
        ]);
        DB::beginTransaction();
        try {
            Excel::import(new PetugasImport, $request->file('excelFile'));
            DB::commit();
            return redirect()->route('admin.master.petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.petugas.index')->with('error', $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new PetugasExport(), 'petugas.xlsx');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $petuga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $petuga)
    {
        $this->confirmAuthorization('edit');
        return view('pages.admin.master.users.edit', [
            'title' => 'Edit Petugas',
            'petuga' => $petuga
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $petuga)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            'password' => 'nullable|confirmed',
        ]);
        DB::beginTransaction();
        try {
            if (!$request->password) {
                $petuga->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);
                $phone = UserCredential::where('user_id', $petuga->id)->first();
                $phone->update([
                    'phone_number' => $request->phone_number
                ]);
            } else {
                $petuga->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);
                $phone = UserCredential::where('user_id', $petuga->id)->first();
                $phone->update([
                    'phone_number' => $request->phone_number
                ]);
            }
            DB::commit();
            return redirect()->route('admin.master.petugas.index')->with('success', 'Petugas berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.petugas.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $petuga)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            UserCredential::where('user_id', $petuga->id)->delete();
            $petuga->delete();
            DB::commit();
            return redirect()->route('admin.master.petugas.index')->with('success', 'Petugas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.master.petugas.index')->with('error', $e->getMessage());
        }
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . "." . $operation)) {
            throw new AuthorizationException("Anda tidak memiliki hak akses untuk melakukan operasi ini.");
        }
    }
}
