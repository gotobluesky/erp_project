<?php
    $chatgpt = Utility::getValByName('enable_chatgpt');
?>

<?php echo e(Form::model($overtime, ['route' => ['overtime.update', $overtime->id], 'method' => 'PUT'])); ?>

<div class="modal-body">

    <?php if($chatgpt == 'on'): ?>
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="<?php echo e(route('generate', ['overtime'])); ?>" data-bs-toggle="tooltip" data-bs-placement="top"
            title="<?php echo e(__('Generate')); ?>" data-title="<?php echo e(__('Generate Content With AI')); ?>">
            <i class="fas fa-robot"></i><?php echo e(__(' Generate With AI')); ?>

        </a>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('title', __('Overtime Title'))); ?>

                <?php echo e(Form::text('title', null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter Title'])); ?>

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo e(Form::label('number_of_days', __('Number Of Days'))); ?>

                <?php echo e(Form::number('number_of_days', null, ['class' => 'form-control ', 'required' => 'required','step' => '0.01'])); ?>

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo e(Form::label('hours', __('Hours'))); ?>

                <?php echo e(Form::number('hours', null, ['class' => 'form-control ', 'required' => 'required','step' => '0.01'])); ?>

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo e(Form::label('rate', __('Rate'))); ?>

                <?php echo e(Form::number('rate', null, ['class' => 'form-control ', 'required' => 'required','step' => '0.01'])); ?>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/ix8ccsto9l8d/public_html/f100.com.mx/nomina/resources/views/overtime/edit.blade.php ENDPATH**/ ?>