	    <!--End Dashboard Content-->

	    </div>
	    <!-- End container-fluid-->

	    </div><!--End content-wrapper-->
	    <!--Start Back To Top Button-->
	    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>

	    <!--Start footer-->
	    <footer class="footer bg-transparent border-light">
	        <div class="container">
	            <div class="text-center">
	                Copyright © 2023 | FUO | School of Post Graduate Studies.
	            </div>
	        </div>
	    </footer>
	    <!--End footer-->

	    </div><!--End wrapper-->

	    <!-- Bootstrap core JavaScript-->
	    <script src="../assets/js/popper.min.js"></script>
	    <script src="../assets/js/bootstrap.min.js"></script>

	    <!-- simplebar js -->
	    <script src="../assets/plugins/simplebar/js/simplebar.js"></script>
	    <!-- waves effect js -->
	    <script src="../assets/js/waves.js"></script>
	    <!-- sidebar-menu js -->
	    <script src="../assets/js/sidebar-menu.js"></script>
	    <!-- Custom scripts -->
	    <script src="../assets/js/app-script.js"></script>

	    <!-- Vector map JavaScript -->
	    <script src="../assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
	    <script src="../assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
	    <!-- Chart js -->
	    <script src="../assets/plugins/Chart.js/Chart.min.js"></script>
	    <!-- Index js -->
	    <script src="../assets/js/index2.js"></script>

	    <!--Data Tables js-->
	    <script src="../assets/plugins/bootstrap-datatable/js/jquery.dataTables.min.js"></script>
	    <script src="../assets/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js"></script>
	    <script src="../assets/plugins/bootstrap-datatable/js/dataTables.buttons.min.js"></script>
	    <script src="../assets/plugins/bootstrap-datatable/js/buttons.bootstrap4.min.js"></script>
	    <script src="../assets/plugins/bootstrap-datatable/js/jszip.min.js"></script>
	    <script src="../assets/plugins/bootstrap-datatable/js/pdfmake.min.js"></script>
	    <script src="../assets/plugins/bootstrap-datatable/js/vfs_fonts.js"></script>
	    <script src="../assets/plugins/bootstrap-datatable/js/buttons.html5.min.js"></script>
	    <script src="../assets/plugins/bootstrap-datatable/js/buttons.print.min.js"></script>
	    <script src="../assets/plugins/bootstrap-datatable/js/buttons.colVis.min.js"></script>

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