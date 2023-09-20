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

        $tasks = Task::all();
        // dd($tasks);
        return response()->json([
            'code' => 200,
            'message'   => 'success',
            'data'      => TaskResource::collection($tasks),
        ], Response::HTTP_OK);
    }

    // public function store(Request $request)
    // {
    //     $request->validate(
    //         [
    //             'name' => 'required',
    //             'due_date' => 'required',
    //             'status' => 'required',
    //             'file' => ['max:5000', 'mimes:pdf,jpeg,png'],
    //         ],
    //         [
    //             'file.max' => 'The file size exceed 5 mb',
    //             'file.mimes' => 'Must be a file of type: pdf,jpeg,png',
    //         ],          
    //         $request->all()
    //     );

    //     DB::beginTransaction();
    //     try {
    //     Task::create([
    //         'name' => $request->name,
    //         'detail' => $request->detail,
    //         'due_date' => $request->due_date,
    //         'status' => $request->status,
    //         'user_id' => Auth::user()->id,
    //     ]);
    // }

    //     $file = $request->file('file');
    //     if ($file) {
    //         $filename = $file->getClientOriginalName();
    //         $path = $file->storePubliclyAs(
    //             'tasks',
    //             $file->hashName(),
    //             'public'
    //         );

    //         TaskFile::create([
    //             'task_id' => $task->id,
    //             'filename' => $filename,
    //             'path' => $path,
    //         ]);
    //     }
    //     $tasks = Task::all();
    //     DB::commit();
    //     return response()->json([
    //         'code'=>200,
    //         'message'=> 'Task created successfully',
    //     ]);
    //     DB::rollBack();
    //     return response()->json([
    //         'code'=>200,
    //         'message'=> 'Task created unsuccessfully',
    //     ]);
    // }

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

        foreach ($task->files as file) {
            Storage::disk('public')->delete($file->path);
            $file->delete();
        }

        $task->delete();

        return response()->json([
            'message'   => 'Taks ' . $task->name . ' successfully deleted'
        ], Response::HTTP_OK);
    }
}
