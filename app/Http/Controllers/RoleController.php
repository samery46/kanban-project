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

        // if (Gate::allows('viewAnyRoles', User::class)) {
        //     $permissions = Permissions::all();
        // } else {
        //     $permissions = Permissions::where('user_id', Auth::user()->id)->get();
        // }
        Gate::authorize('viewAnyRoles', Role::class);
        return view('roles.index', [
            'pageTitle' => $pageTitle,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        Gate::authorize('createNewRoles', Role::class);
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

        Gate::authorize('updateAnyRoles', Role::class);

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

        Gate::authorize('updateAnyRoles', Role::class);
        $pageTitle = 'Edit Role Permissions';
        $role      = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        // Gate::authorize('manageUserRoles', Role::class);
        return view('roles.edit', [
            'role'        => $role,
            'pageTitle'   => $pageTitle,
            'permissions' => $permissions
        ]);
    }

    public function update($id, Request $request)
    {

        Gate::authorize('updateAnyRoles', Role::class);
        $request->validate([
            'name'          => ['required'],
            'permissionIds' => ['required'],
        ]);

        $role = Role::with('permissions')->findOrFail($id);

        // Gate::authorize('manageUserRoles', Role::class);
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

        Gate::authorize('deleteAnyRoles', Role::class);
        $pageTitle = 'Delete Role Permission';
        $role      = Role::findOrFail($id); // diperbaharui        

        return view('roles.delete', ['pageTitle' => $pageTitle, 'role' => $role]);
    }

    public function destroy($id)
    {

        Gate::authorize('deleteAnyRoles', Role::class);
        $role      = Role::with('permissions')->findOrFail($id); // diperbaharui

        DB::beginTransaction();
        try {

            $role->delete();
            DB::commit();
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        // return redirect()->route('roles.index');
    }
}
