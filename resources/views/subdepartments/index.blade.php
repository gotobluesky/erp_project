
@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Subdepartments') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Subdepartments') }}</li>
@endsection

@section('action-button')
    <a href="{{ route('subdepartments.create') }}" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i> {{ __('Create Subdepartment') }}
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th width="200px">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subdepartments as $subdepartment)
                            <tr>
                                <td>{{ $subdepartment->name }}</td>
                                <td>{{ $subdepartment->department->name }}</td>
                                <td class="Action">
                                    <a href="{{ route('subdepartments.edit', $subdepartment->id) }}" class="btn btn-sm btn-info">
                                        <i class="ti ti-pencil"></i> {{ __('Edit') }}
                                    </a>

                                    <form method="POST" action="{{ route('subdepartments.destroy', $subdepartment->id) }}" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this subdepartment?') }}')">
                                            <i class="ti ti-trash"></i> {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

