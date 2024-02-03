<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class TaskController extends Controller
{
    //
    public function index(Request $request)
    { 
        $perPageOptions = [10, 20, 50];  
        $perPage = $request->input('per_page', 10);  
        $request->validate([
            'per_page' => 'in:' . implode(',', $perPageOptions),
        ]);


        $status = $request->input('status');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');

        $tasksQuery = Task::where('user_id', Auth::id());
      
        if ($request->has('search')) {
            $tasksQuery->where('title', 'like', '%' . $request->input('search') . '%');
        }

       
        if ($status) {
            $tasksQuery->where('status', $status);
        }

       
        $tasksQuery->orderBy($orderBy, $orderDirection);

        $tasks = $tasksQuery->paginate($perPage);

        return view('tasks.index', compact('tasks', 'perPage', 'perPageOptions'));
    }
 
    public function create()
    {
        return view('tasks.create');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'title' => 'required|max:100',
            'description' => 'nullable',
            'status' => 'in:to-do,in-progress,done',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
             
        ]);
        $validatedData['user_id'] = Auth::id();
        $validatedData['is_draft'] = $request->has('is_draft') ? 1 : 0;

       
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('task_images', 'public');
            $validatedData['image'] = $imagePath;
        }

       
        $task = Task::create($validatedData);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

     /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:100',
            'description' => 'nullable',
            'status' => 'in:to-do,in-progress,done',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            
        ]);

       
        if ($request->hasFile('image')) {
           
            if ($task->image) {
                Storage::disk('public')->delete($task->image);
            }

           
            $imagePath = $request->file('image')->store('task_images', 'public');
            $validatedData['image'] = $imagePath;
        }

       
        $validatedData['is_draft'] = $request->has('is_draft') ? 1 : 0;
         
        $task->update($validatedData);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /** 
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
      
        if ($task->image) {
           
            Storage::disk('public')->delete($task->image);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
    public function moveToTrash($id)
    {
        $task = Task::findOrFail($id);
         
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task moved to trash successfully!');
    }
}
