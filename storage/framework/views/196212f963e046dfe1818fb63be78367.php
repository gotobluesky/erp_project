<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Leave Calender')); ?>

<?php $__env->stopSection(); ?>

<?php
    $setting = App\Models\Utility::settings();
?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Leave')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <a href="<?php echo e(route('leave.index')); ?>" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
        data-bs-original-title="<?php echo e(__('List View')); ?>">
        <i class="ti ti-list"></i>
    </a>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Leave')): ?>
        <a href="#" data-url="<?php echo e(route('leave.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Leave')); ?>"
            data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="<?php echo e(__('Create')); ?>">
            <i class="ti ti-plus"></i>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5><?php echo e(__('Calendar')); ?></h5>
                            <input type="hidden" id="path_admin" value="<?php echo e(url('/')); ?>">
                        </div>
                        <div class="col-lg-6">
                            
                                <label for=""></label>
                                <?php if(isset($setting['is_enabled']) && $setting['is_enabled'] == 'on'): ?>
                                    <select class="form-control" name="calender_type" id="calender_type"
                                    style="float: right;width: 155px;" onchange="get_data()">
                                        <option value="google_calender"><?php echo e(__('Google Calendar')); ?></option>
                                        <option value="local_calender" selected="true"><?php echo e(__('Local Calendar')); ?></option>
                                    </select>
                                <?php endif; ?>
                            
                        </div>
                        <div class="card-body">
                            <div id='calendar' class='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4"><?php echo e(__('Leaves')); ?></h4>
                    <ul class="event-cards list-group list-group-flush mt-3 w-100">
                        <?php $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item card mb-3">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-calendar-event"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="  text-primary">
                                                    <a href="#" data-size="lg"
                                                        data-url="<?php echo e(route('leave.action', $leave->id)); ?>"
                                                        data-ajax-popup="true" data-title="<?php echo e(__('Edit Event')); ?>"
                                                        class="text-primary"><?php echo e(!empty(\Auth::user()->getLeaveType($leave->leave_type_id)) ? \Auth::user()->getLeaveType($leave->leave_type_id)->title : ''); ?>

                                                    </a>
                                                </h5>
                                                <div class="card-text small text-dark">
                                                    <?php echo e(date('d F Y, h:m A', strtotime($leave->start_date)) . ' To '); ?>

                                                    <?php echo e(date('d F Y, h:m A', strtotime($leave->end_date))); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/main.min.js')); ?>"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            get_data();
        });

        function get_data() {
            var calender_type = $('#calender_type :selected').val();
            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('google_calender');
            if(calender_type==undefined){
                calender_type='local_calender';
            }
            $('#calendar').addClass(calender_type);

            $.ajax({
                url: $("#path_admin").val() + "/leave/get_leave_data",
                method: "POST",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    'calender_type': calender_type
                },
                success: function(data) {
                    console.log(data);
                    (function() {
                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            buttonText: {
                                timeGridDay: "<?php echo e(__('Day')); ?>",
                                timeGridWeek: "<?php echo e(__('Week')); ?>",
                                dayGridMonth: "<?php echo e(__('Month')); ?>"
                            },
                            slotLabelFormat: {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                            },
                            themeSystem: 'bootstrap',
                            // slotDuration: '00:10:00',
                            allDaySlot: true,
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            events: data,
                            height: 'auto',
                            timeFormat: 'H(:mm)',
                        });
                        calendar.render();
                    })();
                }
            });

        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ix8ccsto9l8d/public_html/f100.com.mx/nomina/resources/views/leave/calender.blade.php ENDPATH**/ ?>