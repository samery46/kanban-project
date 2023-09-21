<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Http\Resources\PermissionResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Untuk menampilkan Response::HTTP

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        if ($roles) {
            return response()->json([
                'message'   => 'All Role are successfully displayed',
                'data'      => RoleResource::collection($roles),
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message'   => 'No successfully role are displayed',
        ], Response::HTTP_NOT_FOUND);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'permissionIds' => ['required'],
        ]);

        // Gate::authorize('updateAnyRoles', Role::class);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
            ]);

            $role->permissions()->sync($request->permissionIds);

            DB::commit();

            return response()->json([
                'message'   => 'Role Created Successfully',
                'data'      => new RoleResource($role),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Role created unsuccessfully',
            ]);
        }
    }

    public function show($id)
    {
        // $article = auth()->user()->articles()->find($id);

        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message'   => 'Role not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message'   => 'Role successfully displayed',
            'data'      => new RoleResource($role),
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {

        // Gate::authorize('updateAnyRoles', Role::class);
        $request->validate([
            'name'          => ['required'],
            'permissionIds' => ['required'],
        ]);

        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json([
                'message'      => 'Role not found',
            ], Response::HTTP_NOT_FOUND);
        }

        // Gate::authorize('manageUserRoles', Role::class);
        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
            ]);
            $role->permissions()->sync($request->permissionIds);
            DB::commit();
            return response()->json([
                'message'   => 'Role ' . $role->name . ' successfully updated',
                'data'      => new RoleResource($role),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Role updated unsuccessfully',
            ]);
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message'      => 'Role not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $role->delete();

        return response()->json([
            'message'   => 'Role ' . $role->name . ' successfully deleted'
        ], Response::HTTP_OK);
    }
}
