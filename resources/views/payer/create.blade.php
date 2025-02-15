
{{ Form::open(['url' => 'payer', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('payer_name', __('Payer Name'), ['class' => 'col-form-label']) }}<span class="text-danger pl-1">*</span>
                {{ Form::text('payer_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Payer Name')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('contact_number', __('Contact Number'), ['class' => 'col-form-label']) }}<span class="text-danger pl-1">*</span>
                {{ Form::text('contact_number', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Contact Number')]) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
