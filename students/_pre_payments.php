<?php include 'includes/header.php'; ?>

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Student Fees > > ></h4>
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
                    <div class="col-12 col-lg-12 col-xl-12 border-light mb-4 mt-4">
                        <div class="card-body">
                            <form method="POST" action="payments.php">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">

                                        <select name="payment_session" id="" class="form-control">
                                            <option value="" selected>-- Select a session -- </option>
                                            <?php
                                            for ($payYr = 2024; $payYr <= date('Y'); $payYr++) {
                                                $paySession = $payYr - 1 . '/' . $payYr;
                                            ?>
                                                <option value="<?= $paySession ?>"> <?= $paySession ?></option>

                                            <?php  }
                                            ?>
                                        </select>

                                    </div>

                                    <div class="col-lg-6 col-md-12">

                                        <select name="payment_semester" id="" class="form-control">
                                            <option value="" selected>-- Select a semester -- </option>
                                            <option value="all"> All</option>
                                            <option value="first"> First Semester</option>
                                            <option value="second"> Second Semester</option>
                                        </select>

                                    </div>
                                    <div class="col-lg-6 col-md-12 mt-4">
                                        <input type="hidden" name="check_session" value="<?= uniqid() ?>">
                                        <button type="submit" id="proceedBtn" class="btn btn-info shadow-info waves-effect waves-light ml-1"> Proceed </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
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
                    // alert('checked');
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

            $('#proceedBtn').on('click', function() {
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