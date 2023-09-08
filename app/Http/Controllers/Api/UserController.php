<?php

namespace App\Http\Controllers\Api;

use App\Models\User; // Ditambahkan
use App\Models\Role; // Ditambahkan
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::all();
        return new UserResource(true, 'List Data User', $users);
    }
}
