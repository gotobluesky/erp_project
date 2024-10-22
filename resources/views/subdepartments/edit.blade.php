@extends('layouts.admin')

@section('page-title')
    {{ __('Edit Subdepartment') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('subdepartments.index') }}">{{ __('Subdepartments') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('subdepartments.update', $subdepartment->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">{{ __('Subdepartment Name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $subdepartment->name }}" required>
                </div>

                <div class="form-group">
                    <label for="department_id">{{ __('Department') }}</label>
                    <select name="department_id" class="form-control" required>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ $subdepartment->department_id == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </form>
        </div>
    </div>
@endsection

