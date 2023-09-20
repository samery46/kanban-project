<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Untuk menampilkan Response::HTTP
use Illuminate\Support\Facades\Storage;

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

        $tasks = Task::query()
            ->where('name', 'like', '%' . request('keyword') . '%')
            ->paginate(10);

        return response()->json([
            'message'   => 'success',
            'data'      => TaskResource::collection($tasks),
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        // $article = auth()->user()->articles()->find($id);

        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Task not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message'   => 'success',
            'data'      => new TaskResource($task),
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Task not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $task->update([
            'name'     => $request->name ?? $task->name,
            'detail'   => $request->detail ?? $task->detail,
            'due_date'   => $request->due_date ?? $task->due_date,
            'status'   => $request->status ?? $task->status,
            'user_id'   => $request->user_id ?? $task->user_id,

        ]);

        return response()->json([
            'message'   => 'User ' . $task->name . ' successfully updated',
            'data'      => new TaskResource($task),
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Task not found',
            ], Response::HTTP_NOT_FOUND);
        }

        // foreach ($task->files as file) {
        //     Storage::disk('public')->delete($file->path);
        //     $file->delete();
        // }

        $task->delete();

        return response()->json([
            'message'   => 'Taks ' . $task->name . ' successfully deleted'
        ], Response::HTTP_OK);
    }
}
