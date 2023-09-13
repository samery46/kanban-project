<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Untuk menampilkan Response::HTTP

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return response()->json([
            'message'   => 'success',
            'data'      => RoleResource::collection($roles),
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $role = Role::create([
            'name'     => $request->name,
        ]);

        return response()->json([
            'message'   => 'success',
            'data'      => new RoleResource($role),
        ]);
    }

    public function show($id)
    {
        // $article = auth()->user()->articles()->find($id);

        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Role not found',
            ]);
        }

        return response()->json([
            'message'   => 'success',
            'data'      => new RoleResource($role),
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Role not found',
            ]);
        }

        $role->update([
            'name'     => $request->name ?? $role->name,
        ]);

        return response()->json([
            'message'   => 'Role ' . $role->name . ' successfully updated',
            'data'      => new RoleResource($role),
        ]);
    }


    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Role not found',
            ]);
        }

        $role->delete();

        return response()->json([
            'message'   => 'Role ' . $role->name . ' successfully deleted'
        ], Response::HTTP_OK);
    }
}
