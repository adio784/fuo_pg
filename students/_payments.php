<?php include 'includes/header.php'; ?>

<?php
// echo $page_path;
// ........ PAYMENT SESSION .............................................................................
$payment_session    = isset($_POST['payment_session']) ? $_POST['payment_session'] : $current_session;
// if (!isset($_POST['payment_session']) || isset($_POST['payment_session']) == "" && $page_path == "payments.php") {
    // echo "<script> alert('Payment session empty'); window.location = 'pre_payments'; </script>";
// }

// Get all available payments
if (isset($_POST['payment_session'])) {
    // echo '<script> alert("HERE ALL PAYMENTS SELCTED"); </script>';
    $payment_session        = $_POST['payment_session'];
    $payment_semester       = $_POST['payment_semester'];
    $getPaymentsQ           = $db->prepare("SELECT * FROM `student_fees` WHERE `status`=? AND program_id=? AND payment_session=? AND payment_semester=?");
    $getPaymentsQ->execute([1, $programId, $payment_session, $payment_semester]);
    $getPayments            = $getPaymentsQ->fetchAll(PDO::FETCH_OBJ);


    // Get Administrative fee
    // $getAdminFee           = $Crud->readAllByThree("student_fees", "status", 1, "AND", "payment_type", 'Administrative fee', 'AND', 'payment_session', $payment_session);
    // foreach ($getAdminFee as $adminFees)
    //   $adminFee = number_format($adminFees->amount_paid);
    
}

?>

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Student Fees > > > <?= $payment_semester . " " . $payment_session ?></h4>
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

                                    <?php $count = 0; ?>

                                    <!-- Iterate thru the existing data -->
                                    <?php
                                    if (isset($getPayments)) {
                                        if ($getPayments === "") {

                                            echo "<tr> <td align='center'> <strong> No record found !!! </strong> </td> </tr>";
                                        } else {

                                            foreach ($getPayments as $record) {
                                                $paymentType    =   $record->payment_type;
                                                $paymentId      =   $record->id;
                                                $paymentSemester=   $record->payment_semester;
                                                $amountPaid     =   $record->amount_paid;
                                                $count++;
                                    ?>

                                            <tr>
                                                <td> <?= $count ?> </td>
                                                <td> <?= $paymentType ?> </td>
                                                <td> <span class="badge bg-secondary text-white p-2"> <?= ucfirst($paymentSemester) . ' Semester' ?> </span></td>
                                                <td> <?= number_format($amountPaid) ?> </td>
                                                <td>
                                                    <form method="POST" action="../app/function/student_actions.php" id="paymentForm">
                                                        <input type="hidden" name="payment_process" value="<?= uniqid() ?>">
                                                        <input type="hidden" name="amount_topay" value="<?= $amountPaid ?>">
                                                        <input type="hidden" name="payment_id" value="<?= $paymentId ?>">
                                                        <input type="hidden" name="payment_session" value="<?= $payment_session ?>">
                                                        <button type="submit" id="proceedBtn" class="btn btn-info shadow-info waves-effect waves-light ml-1"> Pay </button>
                                                    </form>
                                                </td>
                                            </tr>

                                    <?php }
                                        }
                                    } else { ?>
                                    <!-- iteration ends here -->
                                        <tr>
                                            <td rowspan="4"> <strong> No record found !!! Click Payments menu to initiate new transaction </strong> </td>
                                        </tr>
                                    <?php } ?>
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