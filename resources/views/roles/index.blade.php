@extends('layouts.master')

@section('pageTitle', $pageTitle)

@section('main')
  <div class="task-list-container">
    <h1 class="task-list-heading">{{ $pageTitle }}</h1>

    {{-- Menyembunyikan button Add sesuai role CreateNewRoles--}}
    @can('createNewRoles', App\Models\Role::class)
    <div class="task-list-task-buttons">
      <a href="{{ route('roles.create') }}">      
        <button  class="task-list-button">
          <span class="material-icons">add</span>Add Role
        </button>
      </a>      
    </div>
    @endcan

    <div>
      <div class="task-list-table-head">
        <div class="task-list-header-task-name">Role</div>
        <div class="task-list-header-detail">Permissions</div>
      </div>

      @foreach ($roles as $role)
      <div class="table-body">
        <div class="table-body-role-name">
          <p>{{ $role->name }}</p>
        </div>
        <div class="table-body-permission">
          <ul>
            @foreach ($role->permissions->sort() as $permission)
              <li class="table-body-permission-item">
                {{ $permission->description }}
              </li>
            @endforeach
          </ul>
        </div>
        <div class="table-body-links">
        @can('updateAnyRoles', App\Models\Role::class)
          <a href="{{ route('roles.edit', ['id' => $role->id]) }}">Edit</a>
        @endcan
        @can('deleteAnyRoles', App\Models\Role::class)
          <a href="{{ route('roles.delete', ['id' => $role->id]) }}">Delete</a>
        @endcan          
        </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection