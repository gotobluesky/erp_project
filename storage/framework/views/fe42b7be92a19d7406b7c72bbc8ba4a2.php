
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Attendance')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Attendance')); ?></li>
<?php $__env->stopSection(); ?>
<?php
    $company_settings = Utility::settings();
    $attendance_count = count($attendances);
?>
<?php $__env->startSection('content'); ?>
    <style>
        .action-btns {
            width: -1px !important;
            height: 28px;
            border-radius: 9.3552px;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
    </style>

    <div class="col-sm-12">
        <div class="mt-2" id="multiCollapseExample1">
            <?php if(empty($token)): ?>
                <div class="text-center">
                    <p class="items-center text-danger text-capitalize"> <i
                            class="ti ti-alert-circle"></i><?php echo e(__('Please first generate auth token')); ?></p>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <?php echo e(Form::open(['route' => ['biometric-attendance.index'], 'method' => 'get', 'id' => 'attendance_filter'])); ?>


                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-10">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                    <div class="btn-box">
                                        <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                                        <?php echo Form::date(
                                            'start_date',
                                            isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-7 days')),
                                            [
                                                'class' => 'form-control ',
                                                'placeholder' => 'Select Start Date',
                                                'id' => 'start_date',
                                            ],
                                        ); ?>

                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                    <div class="btn-box">
                                        <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>

                                        <?php echo Form::date('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'), [
                                            'class' => 'form-control ',
                                            'placeholder' => 'Select End Date',
                                            'id' => 'end_date',
                                        ]); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <div class="col-auto mt-4">
                                    <a href="#" class="btn btn-sm btn-primary"
                                        onclick="document.getElementById('attendance_filter').submit(); return false;"
                                        data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>" data-bs-original-title="apply">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>

                                    <a href="<?php echo e(route('biometric-attendance.index')); ?>" class="btn btn-sm btn-danger "
                                        data-bs-toggle="tooltip" title="<?php echo e(__('Reset')); ?>"
                                        data-original-title="<?php echo e(__('Reset')); ?>">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>

                                    <?php if($attendance_count > 0): ?>
                                        <input type="button" value="<?php echo e(__('Sync All')); ?>" class="btn btn-primary btn-sm"
                                            style="margin-left: 5px" id="bulk_attendance">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Id')); ?></th>
                                <th><?php echo e(__('Employee Code')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Time')); ?></th>
                                <?php if(Gate::check('Biometric Attendance Synchronize')): ?>
                                    <th width="200px"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        
   <tbody>
    <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($attendance['id']); ?></td>
            <td><?php echo e($attendance['emp_code']); ?></td>
            <td><?php echo e($attendance['employee_name']); ?></td> <!-- Mostrar el nombre del empleado -->
            <td><?php echo e(\Auth::user()->DateTimeFormat($attendance['punch_time'])); ?></td>
            <td class="Action">
                <?php if(Gate::check('Biometric Attendance Synchronize')): ?>
                    <div class="action-btn bg-info ms-2">
                        <form method="POST" action="<?php echo e(route('biometric-attendance.update', $attendance['id'])); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="biometric_emp_id" value="<?php echo e($attendance['emp_code']); ?>">
                            <input type="hidden" name="punch_time" value="<?php echo e($attendance['punch_time']); ?>">

                            <button type="submit" class="btn btn-primary btn-sm" data-ajax-popup="false" data-size="sm" data-bs-toggle="tooltip" title="<?php echo e(__('Sync')); ?>">
                                <?php echo e(__('Sync')); ?>

                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>


                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).on("click", "#bulk_attendance", function() {
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            var url = '<?php echo e(route('biometric-attendance.allsync')); ?>';
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: url,
                type: 'POST', // Assuming the route accepts POST method
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                data: {
                    start_date: start_date,
                    end_date: end_date,
                },
                success: function(data) {
                    // Handle success response
                    if (data.message === 'This employee is already sync.') {
                        show_toastr('Error', data.message, 'error');
                    } else if (data.message === 'Please first create employee or edit employee code.') {
                        show_toastr('Error', data.message, 'error');
                    } else {
                        show_toastr('Success', data.message, 'success');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    show_toastr('Error', status, 'error');
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ix8ccsto9l8d/public_html/f100.com.mx/nomina/resources/views/biometricattendance/index.blade.php ENDPATH**/ ?>