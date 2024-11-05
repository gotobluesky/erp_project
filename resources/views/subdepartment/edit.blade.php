
{{ Form::model($subdepartment, ['route' => ['subdepartment.update', $subdepartment->id], 'method' => 'PUT']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('branch_id', __('Select Branch*'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::select('branch_id', $branch, null, ['class' => 'form-control branch_id', 'required' => 'required', 'id' => 'branch_id']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <div class="form-icon-user" id="department_id">
                    {{ Form::label('department_id', __('Department*'), ['class' => 'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::select('department_id', $department, null, ['class' => 'form-control branch_id', 'required' => 'required', 'id' => 'department_id']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Name SubDepartment'), ['class' => 'form-label']) }}<span class="text-danger pl-1">*</span>
                <div class="form-icon-user">
                    {{ Form::text('name', $subdepartment->name, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Subdepartment Name')]) }}
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}


