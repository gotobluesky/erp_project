
<?php echo e(Form::open(['url' => 'designation', 'method' => 'post'])); ?>

<div class="modal-body">

    <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('branch_id', __('Select Branch*'), ['class' => 'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::select('branch_id', $branches, null, ['class' => 'form-control branch_id', 'required' => 'required', 'id' => 'branch_id'])); ?>

                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <div class="form-icon-user" id="department_id">
                    <?php echo e(Form::label('department_id', __('Department*'), ['class' => 'form-label'])); ?>

                    <select class="form-control department_id" name="department_id" id="department_id"
                        placeholder="Select Department">
                    </select>
                </div>
            </div>
        </div>
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <div class="form-icon-user" id="subdepartment_id">
                    <?php echo e(Form::label('subdepartment_id', __('Subdepartment*'), ['class' => 'form-label'])); ?>

                    <select class="form-control subdepartment_id" name="subdepartment_id" id="subdepartment_id"
                        placeholder="Select Subdepartment">
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?><span class="text-danger pl-1">*</span>
                <div class="form-icon-user">
                    <?php echo e(Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Designation Name')])); ?>

                </div>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger"><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>



    <script>
         $(document).ready(function() {
            var b_id = $('#branch_id').val();
             getDepartment(b_id);
        });
        $(document).on('change', 'select[name=branch_id]', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(bid) {
           
            $.ajax({
                url: '<?php echo e(route('monthly.getdepartment')); ?>',
                type: 'POST',
                data: {
                    "branch_id": bid,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {
                 
                    $('.department_id').empty();
                    var emp_selct = `<select class="form-control department_id" name="department_id" id="choices-multiple"
                                            placeholder="Select Department" >
                                            </select>`;
                    $('.department_div').html(emp_selct);

                    $('.department_id').append('<option value=""> <?php echo e(__('Select Department')); ?> </option>');
                    $.each(data, function(key) {
                        $('.department_id').append('<option value="' + data[key]['id'] + '">' + data[key]['name'] +
                            '</option>');
                    });
                }
            });
        }

        $(document).ready(function() {
            var d_id = $('.department_id').val();
            getSubdepartment(d_id);
        });

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getSubdepartment(department_id);
        });

        function getSubdepartment(did) {

            $.ajax({
                url: '<?php echo e(route('subdepartment.json')); ?>',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {

                    $('.subdepartment_id').empty();
                    var emp_selct = `<select class="form-control subdepartment_id" name="subdepartment_id"
                                                 placeholder="Select Subdepartment" required>
                                            </select>`;
                    $('.subdepartment_div').html(emp_selct);

                    $('.subdepartment_id').append('<option value=""> <?php echo e(__('Select Subdepartment')); ?> </option>');
                    console.log(data)
                    $.each(data, function(key, value) {
                        $('.subdepartment_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                  
                }
            });
        }
    </script>

<?php /**PATH /home/ix8ccsto9l8d/public_html/f100.com.mx/nomina/resources/views/designation/create.blade.php ENDPATH**/ ?>