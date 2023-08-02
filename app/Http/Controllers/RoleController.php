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
        //
    }

    public function update($id, Request $request)
    {
        //
    }

    public function delete($id)
    {
        $pageTitle = 'Delete Role Permission';
        $permissions = Permission::findOrFail($id); // diperbaharui

        Gate::authorize('delete', $permissions);

        return view('roles.delete', ['pageTitle' => $pageTitle, 'permission' => $permissions]);
    }

    public function destroy($id)
    {
        $permissions = Permission::find($id);

        Gate::authorize('delete', $permissions);

        $permissions->delete();
        return redirect()->route('roles.index');
    }
}
