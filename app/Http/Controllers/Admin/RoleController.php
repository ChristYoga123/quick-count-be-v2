<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\RoleDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // define permissions
    public $permissions = "Peran.web";
    /**
     * Display a listing of the resource.
     */
    public function index(RoleDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        $permissionModule = Permission::all();
        // split string by dot
        $permissionModule = $permissionModule->map(function ($item) {
            $item = explode('.', $item->name)[0];
            return $item;
        });
        return $dataTable->render('pages.admin.settings.roles.index', [
            "title" => "Roles",
            "permission" => Permission::all(),
            "modules" => $permissionModule->unique(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            "addName" => "required|string",
            "addPermissions" => "array"
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                "name" => $request->addName
            ]);
            $role->syncPermissions($request->addPermissions);
            DB::commit();
            return redirect()->route('admin.settings.roles.index')->with('success', 'Peran berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.settings.roles.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $this->confirmAuthorization('show');
        $role = $role->load('permissions');
        return response()->json($role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            "editName" => "required|string|unique:roles,name," . $role->id,
            "editPermissions" => "array"
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                "name" => $request->editName
            ]);
            $role->syncPermissions($request->editPermissions);
            DB::commit();
            return redirect()->route('admin.settings.roles.index')->with('success', 'Peran berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.settings.roles.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();
            return redirect()->route('admin.settings.roles.index')->with('success', 'Peran berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.settings.roles.index')->with('error', $e->getMessage());
        }
    }

    // check if user has permission
    private function confirmAuthorization($operation)
    {
        if (!auth()->user()->can($this->permissions . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki izin untuk mengakses halaman ini');
        }
    }
}
