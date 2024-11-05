<?php
    // $logo = asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    
    $company_logo = Utility::getValByName('company_logo');
?>
<div class="modal-body">
    <div class="text-md-end mb-2">
        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
            title="<?php echo e(__('Download')); ?>" onclick="saveAsPDF()"><span class="fa fa-download"></span></a>
        <?php if(\Auth::user()->type == 'company' || \Auth::user()->type == 'hr'): ?>
            <a title="Mail Send" href="<?php echo e(route('payslip.send', [$employee->id, $payslip->salary_month])); ?>"
                class="btn btn-sm btn-warning"><span class="fa fa-paper-plane"></span></a>
        <?php endif; ?>
    </div>
    <div class="invoice" id="printableArea">
        <div class="row">
            <div class="col-form-label">
                <div class="invoice-number">
                    <img src="<?php echo e($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'dark_logo.png')); ?>"
                        width="170px;">
                </div>


                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                

                            </div>
                            <hr>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    <address>
                                        <strong><?php echo e(__('Name')); ?> :</strong> <?php echo e($employee->name); ?><br>
                                        <strong><?php echo e(__('Position')); ?> :</strong> <?php echo e($employee->designation->name); ?><br>
                                        <strong><?php echo e(__('Salary Date')); ?> :</strong>
                                        <?php echo e(\Auth::user()->dateFormat($payslip->start)); ?> - <?php echo e(\Auth::user()->dateFormat($payslip->end)); ?><br>
                                    </address>
                                </div>
                                <div class="col-md-6 text-end">
                                    <address>
                                        <strong><?php echo e(\Utility::getValByName('company_name')); ?> </strong><br>
                                        <?php echo e(\Utility::getValByName('company_address')); ?> ,
                                        <?php echo e(\Utility::getValByName('company_city')); ?>,<br>
                                        <?php echo e(\Utility::getValByName('company_state')); ?>-<?php echo e(\Utility::getValByName('company_zipcode')); ?><br>
                                        <strong><?php echo e(__('Salary Slip')); ?> :</strong> <br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table  table-md">
                                   <table class="table table-striped table-hover table-md">
                                    <tbody>
                                        <tr class="font-weight-bold">
                                             <tr class="font-weight-bold">
                                            <th><?php echo e(__('Earning')); ?></th>
                                            <th><?php echo e(__('Title')); ?></th>
                                            <th><?php echo e(__('type')); ?></th>
                                            <th><?php echo e(__('Amount')); ?></th>
                                        </tr>

                                        
                                        </tr>

                                    
                                                <tr>
                                                    <td>Net Salary</td>
                                                    <td>-</td>
                                                   <td>-</td>
                                                   <td>     <?php echo e(\Auth::user()->priceFormat($payslip->net_payble)); ?></td>
                                                </tr>
                                                 <tr>
                                                     <td>Salario Sobre</td>
                                                    <td>-</td>
                                                   <td>-</td>
                                                   <td>     <?php echo e(\Auth::user()->priceFormat((($employee->saltots-$employee->salary*7)/7)*$payslip->labor_days)); ?></td>
                                                </tr>
                                                <tr>
                                                     <td>Sunday</td>
                                                    <td>-</td>
                                                   <td>-</td>
                                                   <td>     <?php echo e(\Auth::user()->priceFormat($payslip->sunday)); ?></td>
                                                </tr>
                                              
                                    </tbody>
                                </table>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                              <tbody>
                                        <tr class="font-weight-bold">
                                             <tr class="font-weight-bold">
                                            <th><?php echo e(__('Deduction')); ?></th>
                                            <th><?php echo e(__('Title')); ?></th>
                                            <th><?php echo e(__('type')); ?></th>
                                            <th><?php echo e(__('Amount')); ?></th>
                                        </tr>

                                        
                                        </tr>

                                      <?php $basic_deduction=json_decode($payslip->basic_deduction);?>
                                                <tr>
                                                    <td>IMSS</td>
                                                    <td>-</td>
                                                   <td>-</td>
                                                   <td><?php echo e(number_format($basic_deduction->imss,4)); ?></td>
                                                </tr>
                                                 <tr>
                                                     <td>ISR</td>
                                                    <td>-</td>
                                                   <td>-</td>
                                                   <td><?php echo e(number_format($basic_deduction->isr, 4)); ?></td>
                                                </tr>
                                                 <tr>
                                                     <td>SUBSIDIO</td>
                                                    <td>-</td>
                                                   <td>-</td>
                                                   <td><?php echo e(number_format($basic_deduction->subsidio, 2)); ?></td>
                                                </tr>
                                           
                                               
                                           
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-8">

                                </div>
                                <div class="col-lg-4 text-right text-sm">
                                    <div class="invoice-detail-item pb-2">
                                        <div class="invoice-detail-name font-weight-bold"><?php echo e(__('Total Earning')); ?>

                                        </div>
                                        <div class="invoice-detail-value">
                                            <?php echo e(\Auth::user()->priceFormat((($employee->saltots-$payslip->salary*7)/7)*$payslip->labor_days+$payslip->sunday)); ?></div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name font-weight-bold"><?php echo e(__('Total Deduction')); ?>

                                        </div>
                                        <div class="invoice-detail-value">
                                            <?php echo e(\Auth::user()->priceFormat($basic_deduction->imss+$basic_deduction->isr-$basic_deduction->subsidio)); ?></div>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name font-weight-bold"><?php echo e(__('Net Salary')); ?></div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                            <?php echo e(\Auth::user()->priceFormat((($employee->saltots-$payslip->salary*7)/7)*$payslip->labor_days-$basic_deduction->imss+$basic_deduction->isr-$basic_deduction->subsidio)); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right pb-2 text-sm">
                    <div class="float-lg-left mb-lg-0 mb-3 ">
                        <p class="mt-2"><?php echo e(__('Employee Signature')); ?></p>
                    </div>
                    <p class="mt-2 "> <?php echo e(__('Paid By')); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
<script>
    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,
            filename: '<?php echo e($employee->name); ?>',
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 4,
                dpi: 72,
                letterRendering: true
            },
            jsPDF: {
                unit: 'in',
                format: 'A4'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>
<?php /**PATH /home/ix8ccsto9l8d/public_html/f100.com.mx/nomina/resources/views/payslip/pdf.blade.php ENDPATH**/ ?>