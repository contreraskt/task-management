<!-- resources/views/tasks/_form.blade.php -->
<div class="container mt-4">
    <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        @if(isset($task))
            @method('PUT')
            <h2 class="mb-4">Edit Task</h2>
        @else
            <h2 class="mb-4">Create New Task</h2>
        @endif

        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" name="title" value="{{ old('title', isset($task) ? $task->title : '') }}" class="form-control" required maxlength="100">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea name="description" class="form-control">{{ old('description', isset($task) ? $task->description : '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select name="status" class="form-select">
                <option value="to-do" {{ (old('status', isset($task) ? $task->status : '') == 'to-do') ? 'selected' : '' }}>To-Do</option>
                <option value="in-progress" {{ (old('status', isset($task) ? $task->status : '') == 'in-progress') ? 'selected' : '' }}>In Progress</option>
                <option value="done" {{ (old('status', isset($task) ? $task->status : '') == 'done') ? 'selected' : '' }}>Done</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image:</label>
            <input type="file" name="image" class="form-control">
            
            <!-- Display the uploaded image as a thumbnail if it exists -->
            @if(isset($task) && $task->image)
                <div class="mt-2">
                    <p>Image Path: {{ asset('storage/' . $task->image) }}</p>
                    <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" class="img-thumbnail thumbnail-image">
                </div>
            @endif
            
        </div>

        <div class="mb-3 form-check form-switch">
            <input type="checkbox" name="is_draft" {{ old('is_draft', isset($task) ? $task->is_draft : 0) ? 'checked' : '' }} class="form-check-input">
            <label class="form-check-label">Draft</label>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($task) ? 'Update Task' : 'Create Task' }}</button>
    </form>
</div>
