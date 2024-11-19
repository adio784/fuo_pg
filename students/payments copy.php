<?php include 'includes/header.php'; ?>

<?php
echo $_POST['payment_session'];
if (!isset($_POST['payment_session']) || isset($_POST['payment_session']) == '' && $page_path == "payments.php") {
    header('location: pre_payments');
}

?>

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Student Fees > > > <?= $payment_session  ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admission_home">Home</a></li>
                    <li class="breadcrumb-item"><a href="javaScript:void();">Application</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fees</li>
                </ol>
            </div>
        </div>

        <div class="card mt-3 shadow-none border border-light">
            <div class="card-content">
                <div class="row row-group m-0">
                    <div class="col-12 col-lg-5 col-xl-3 border-light">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h4 class="text-info" id="fees_count">0</h4>
                                    <span>Total Selection</span>
                                </div>
                                <div class="align-self-center w-circle-icon rounded bg-info shadow-info">
                                    <i class="icon-basket-loaded text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-7 col-xl-7 border-light">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body text-left">
                                    <form method="POST" action="../app/function/student_actions.php" id="paymentForm">
                                        <h4 class="text-danger"><input type="text" name="amount_topay" id="school_fee" class="form-control" placeholder="total amount left" readonly></h4>
                                        <input type="hidden" name="payment_purpose" id="payment_purpose">
                                        <input type="hidden" name="payment_session" value="<?= $payment_session ?>">
                                        <input type="hidden" name="payment_process" value="<?= uniqid() ?>">
                                        <strong>Amount to pay</strong>
                                </div>
                                <button type="submit" id="proceedBtn" class="btn btn-info shadow-info waves-effect waves-light ml-1"> Proceed </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-table"></i> All Fees</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="75px">*</th>
                                        <th>Purpose</th>
                                        <th>Semester</th>
                                        <th>Amount to pay</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> <input type="checkbox" class="fee_check form-control" disabled> </td>
                                        <th> <strong> Administrative Fee</strong></th>
                                        <th> <span class="badge bg-secondary text-white p-2">All Semester</span></th>
                                        <th> <?= $adminFee ?> </th>
                                        <th><button type="submit" id="proceedBtn" class="btn btn-info shadow-info waves-effect waves-light ml-1"> Pay </button></th>
                                    </tr>

                                    <?php $count = 1; ?>

                                    <!-- Iterate thru the existing data -->
                                    <?php

                                    if ($getPayments === "") {

                                        echo "<tr> <td align='center'> <strong> No record found !!! </strong> </td> </tr>";
                                    } else {
                                        foreach ($getPayments as $record) {

                                            if ($record->payment_type == 'Administrative fee') {
                                                continue;
                                            }
                                            $paymentType    =   $record->payment_type;
                                            $paymentSemester =   $record->payment_semester;
                                            $amountPaid     =   $record->amount_paid;
                                            $count++;
                                    ?>
                                            <form method="POST" action="../app/function/student_actions.php" id="paymentForm">
                                            <tr>
                                                <td><input type="checkbox" name="paymentPurpose" data-id="<?= $paymentType ?>=<?= $amountPaid ?>" id="feecheck<?= $count ?>" class="fee_check form-control" value="<?= $amountPaid ?>"> </td>
                                                <td> <?= $paymentType ?> </td>
                                                <td> <span class="badge bg-secondary text-white p-2"> <?= ucfirst($paymentSemester) . ' Semester' ?> </span></td>
                                                <td> <?= number_format($amountPaid) ?> </td>
                                                <td>
                                                    <button type="submit" id="proceedBtn" class="btn btn-info shadow-info waves-effect waves-light ml-1"> Pay </button>
                                                </td>
                                            </tr>
                                            </form>
                                    <?php }
                                    } ?>

                                    <!-- iteration ends here -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>*</th>
                                        <th>Purpose</th>
                                        <th>Semester</th>
                                        <th>Amount to pay</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- End Row-->

    </div>
    <!-- End container-fluid-->

</div><!--End content-wrapper-->
<!--Start Back To Top Button-->
<a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
<!--End Back To Top Button-->



<?php include "includes/footer.php" ?>

<script>
    $(document).ready(function() {
        //Default data table
        $('#default-datatable').DataTable();


        var table = $('#example').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
        });

        table.buttons().container()
            .appendTo('#example_wrapper .col-md-6:eq(0)');

    });
</script>

<script>
    $(document).ready(function() {
        $('.fee_check').on('change', function() {

            var mycheck_val = $(this).attr('id');
            var purpose_val = $(this).attr('data-id');
            var existingSchoolFee = parseInt($('#school_fee').val()) || 0;
            var total_fee = existingSchoolFee;
            var checkedBox = $('#' + mycheck_val);
            var check_box_value = parseInt(checkedBox.val()); //parseFloat($(this).val());

            // if ( checkedBox == true ) {
            if (checkedBox.is(':checked')) {
                total_fee += check_box_value || 0;
                $("#payment_purpose").val(',' + purpose_val);
                alert(purpose_val);
            } else {
                // alert('unchecked');
                total_fee -= check_box_value || 0;
                var replacWrd = ',' + purpose_val;
            }

            // update the school fee field
            $('#school_fee').val(total_fee);

            // updateSchoolFee();
            updateCounterFee();
        });
        // fee_check
        // fees_count
        // school_fee


        function updateCounterFee() {
            var checkedFees = $('.fee_check:checked').length;
            $('#fees_count').text(checkedFees);
        }

    })
</script>

<script>
    $(document).ready(function() {

        $('#proceedBtn').on('click', function(evt) {
            // evt.preventDefault();
            var selectedCheckboxes = $('.fee_check:checked').map(function() {
                return $(this).attr('data-id');
            }).get();

            $("#payment_purpose").val(selectedCheckboxes);
            // Display the array in the console (you can use it as needed)
            console.log(selectedCheckboxes);
        });
    });
</script>

<script>
    $(document).ready(function() {

        var authID = "<?= (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : '' ?>";
        var pay_success = "<?= (isset($_GET['pay_success'])) ? $_GET['pay_success'] : '' ?>";
        var pay_error = "<?= (isset($_GET['error'])) ? $_GET['error'] : "" ?>";


        var msg = 'To make payment for school fee, type in the amount and it will be automatically added to cart, select other fees by clicking the checkboxe before each fee. \n Click on Proceed to make payment.';

        if (authID && pay_success == "" && pay_error == "") {

            Lobibox.notify('success', {
                msg: msg,
                class: 'lobibox-notify-success',
                title: "Welcome back! ",
                position: 'top right',
                icon: false,
                sound: 'sound2.mp3',
                delay: 15000,
                theme: 'minimal',
            });

        }
        if (pay_success != "") {

            Lobibox.notify('info', {
                msg: pay_success,
                class: 'lobibox-notify-success',
                title: "Payment ! ",
                position: 'top right',
                icon: false,
                theme: 'minimal',
            });

        }
        if (pay_error != "") {

            Lobibox.notify('warning', {
                msg: pay_error,
                class: 'lobibox-notify-success',
                title: "Payment ! ",
                position: 'top right',
                icon: false,
                theme: 'minimal',
            });

        }



        $("#paymentForm").submit(function() {
            $("#proceedBtn").html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');
            $("#proceedBtn").prop("disabled", true);
        });

    })
</script>

</body>

</html>