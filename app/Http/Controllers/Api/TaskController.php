<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Untuk menampilkan Response::HTTP

class TaskController extends Controller
{
    //
    public function home()
    {
        $tasks = Task::get();

        $completed_count = $tasks
            ->where('status', Task::STATUS_COMPLETED)
            ->count();

        $uncompleted_count = $tasks
            ->whereNotIn('status', Task::STATUS_COMPLETED)
            ->count();

        return response()->json([
            'completed_count' => $completed_count,
            'uncompleted_count' => $uncompleted_count,
        ], Response::HTTP_OK);
    }

    public function index()
    {
        $tasks = Task::all();
        return new TaskResource(true, 'List Data Tasks', $tasks);
    }
}
