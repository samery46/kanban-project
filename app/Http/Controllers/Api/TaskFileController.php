<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\TaskFileResource;
use Illuminate\Support\Facades\Storage;


class TaskFileController extends Controller
{

    public function index()
    {

        $file = TaskFile::all();

        if ($file) {
            return response()->json([
                'message'   => 'All file are successfully displayed',
                'data'      => TaskFileResource::collection($file),
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message'   => 'No successfully file are displayed',
        ], Response::HTTP_NOT_FOUND);
    }
    public function store($task_id, Request $request)
    {
        $task = Task::find($task_id);
        // dd($task);
        if (!$task) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Task not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $request->validate(
            [
                'file' => ['required', 'mimes:pdf,jpeg,png', 'max:5000'],
            ],
            [
                'file.max' => 'The file size exceed 5 mb',
                'file.mimes' => 'Must be a file of type: pdf,jpeg,png',
            ],
            $request->all()
        );

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->storePubliclyAs('tasks', $file->hashName(), 'public');

        TaskFile::create([
            'task_id' => $task->id,
            'filename' => $filename,
            'path' => $path,
        ]);

        return response()->json([

            'message'   => 'Task File Created Successfully',
            'tasks' => new TaskFileResource($task)
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $file = TaskFile::find($id);

        if (!$file) {
            return response()->json([
                'message'      => 'File not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $filePath = storage_path('app/public/' . $file->path);

        if (file_exists($filePath)) {
            return response()->json([
                'data'   => 'File path :' . $filePath
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message'      => 'Error, File Path Not Found ',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        $file = TaskFile::find($id);

        if (!$file) {
            return response()->json([
                'message'      => 'File not found',
            ], Response::HTTP_NOT_FOUND);
        }

        Storage::disk('public')->delete($file->path);
        $file->delete();
        return response()->json([
            'message'   => 'File ' . $file->name . ' successfully deleted'
        ], Response::HTTP_OK);
    }
}
