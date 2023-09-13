<?php

namespace App\Http\Controllers\Api;

use App\Models\User; // Ditambahkan
use App\Models\Role; // Ditambahkan
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response; // Untuk menampilkan Response::HTTP
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();

        return response()->json([
            'message'   => 'success',
            'data'      => UserResource::collection($users),
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'     => $request->email,
            'password'     => $request->password,
            'role_id'     => $request->role_id,
        ]);

        return response()->json([
            'message'   => 'success',
            'data'      => new UserResource($user),
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        // $article = auth()->user()->articles()->find($id);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'User not found',
            ]);
        }

        return response()->json([
            'message'   => 'success',
            'data'      => new UserResource($user),
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'User not found',
            ]);
        }

        $user->update([
            'name'     => $request->name ?? $user->name,
            'email'   => $request->email ?? $user->email,
            'password'   => $request->password ?? $user->password,
            'role_id'   => $request->role_id ?? $user->role_id,
        ]);

        return response()->json([
            'message'   => 'User ' . $user->name . ' successfully updated',
            'data'      => new UserResource($user),
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'User not found',
            ]);
        }

        $user->delete();

        return response()->json([
            'message'   => 'User ' . $user->name . ' successfully deleted'
        ], Response::HTTP_OK);
    }
}
