@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['url' => 'loan', 'method' => 'post']) }}
{{ Form::hidden('employee_id', $employee->id, []) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['loan']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}
            {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter Title']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('loan_option', __('Loan Options'), ['class' => 'col-form-label']) }}<span class="text-danger pl-1">*</span>
            {{ Form::select('loan_option', $loan_options, null, ['class' => 'form-control select2','required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('type', __('Type'), ['class' => 'col-form-label']) }}
            {{ Form::select('type', $loan, null, ['class' => 'form-control select2 amount_type', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Loan Amount'), ['class' => 'col-form-label amount_label']) }}
            {{ Form::number('amount', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01','placeholder'=>'Enter Amount']) }}
        </div>

        {{-- <div class="form-group col-md-6">
            {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }}
            {{ Form::text('start_date', null, ['class' => 'form-control d_week','required' => 'required','autocomplete'=>'off']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }}
            {{ Form::text('end_date', null, ['class' => 'form-control d_week', 'required' => 'required','autocomplete'=>'off']) }}
        </div> --}}
        <div class="form-group">
            {{ Form::label('reason', __('Reason'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('reason', null, ['class' => 'form-control', 'rows' => 3, 'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
