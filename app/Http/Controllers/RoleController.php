<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role; // Ditambahkan
use App\Models\Permission; // Ditambahkan
use Illuminate\Support\Facades\DB; // Ditambahkan
use Illuminate\Support\Facades\Gate; // Ditambahkan

class RoleController extends Controller
{
    //
    public function index()
    {
        $pageTitle = 'Role Lists';
        $roles = Role::all();

        return view('roles.index', [
            'pageTitle' => $pageTitle,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $pageTitle = 'Add Role';
        $permissions = Permission::all();
        return view('roles.create', [
            'pageTitle' => $pageTitle,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'permissionIds' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
            ]);

            $role->permissions()->sync($request->permissionIds);

            DB::commit();

            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Role Permissions';
        $role      = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        Gate::authorize('manageRoles', Role::class);
        return view('roles.edit', [
            'role'        => $role,
            'pageTitle'   => $pageTitle,
            'permissions' => $permissions
        ]);
    }


    public function update($id, Request $request)
    {
        $request->validate([
            'name'          => ['required'],
            'permissionIds' => ['required'],
        ]);

        $role = Role::with('permissions')->findOrFail($id);

        Gate::authorize('manageRoles', Role::class);
        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
            ]);
            $role->permissions()->sync($request->permissionIds);
            DB::commit();
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete($id)
    {
        $pageTitle = 'Delete Role Permission';
        $role      = Role::with('permissions')->findOrFail($id); // diperbaharui

        Gate::authorize('manageRoles', Role::class);
        return view('roles.delete', ['pageTitle' => $pageTitle, 'role' => $role]);
    }

    public function destroy($id)
    {
        $role      = Role::with('permissions')->findOrFail($id); // diperbaharui

        Gate::authorize('manageRoles', Role::class);
        DB::beginTransaction();
        try {

            $role->delete();
            DB::commit();
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('roles.index');
    }
}
