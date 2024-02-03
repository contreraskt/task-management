<!-- resources/views/tasks/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Task List</h1>

        <!-- Search Form -->
        <form action="{{ route('tasks.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by title" value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
                <label for="per_page" class="form-label">Items Per Page:</label>
                <select name="per_page" id="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach($perPageOptions as $option)
                        <option value="{{ $option }}" {{ $option == $perPage ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            
        </form>
        <!-- Filter Form -->
        <form action="{{ route('tasks.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <select name="status" class="form-select">
                    <option value="" selected>Select Status</option>
                    <option value="to-do" {{ (request('status') == 'to-do') ? 'selected' : '' }}>To-Do</option>
                    <option value="in-progress" {{ (request('status') == 'in-progress') ? 'selected' : '' }}>In Progress</option>
                    <option value="done" {{ (request('status') == 'done') ? 'selected' : '' }}>Done</option>
                </select>
                <button type="submit" class="btn btn-outline-secondary">Filter</button>
            </div>
        </form>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">New Task</a>


        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Task List -->
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Draft</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->is_draft ? 'draft' : 'published' }}</td>
                        <td>{{ $task->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-warning">Move to Trash</button>
                            </form>
                        </td>
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
 
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $tasks->appends(['per_page' => $perPage])->links() }}
        </div>
    </div>
@endsection
