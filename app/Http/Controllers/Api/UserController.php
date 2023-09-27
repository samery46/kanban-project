<?php

namespace App\Http\Controllers\Api;

use App\Models\User; // Ditambahkan
use App\Models\Role; // Ditambahkan
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response; // Untuk menampilkan Response::HTTP
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function index()
    {
        $users = User::all();

        if (Gate::authorize('viewAnyRoles', Role::class)) {
            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'message'   => 'success',
            'data'      => UserResource::collection($users),
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
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
            ], Response::HTTP_NOT_FOUND);
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
            return response()->json(
                [
                    'message'   => 'error',
                    'data'      => 'User not found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if (Gate::authorize('viewAnyRoles', Role::class)) {
            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
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
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return response()->json([
            'message'   => 'User ' . $user->name . ' successfully deleted'
        ], Response::HTTP_OK);
    }
}
