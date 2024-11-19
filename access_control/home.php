<?php include 'includes/header.php'; ?>



<!--Start Dashboard Content-->


<?php if ($role == 'admin' || $role == "super_admin") { ?>
  <!-- Admin Data Analytics -------------------------------------------------------- -->
  <div class="card mt-3 shadow-none border border-light">
    <div class="card-content">
      <div class="row row-group m-0">
        <div class="col-12 col-lg-6 col-xl-3 border-light">
          <div class="card-body">
            <div class="media">
              <div class="media-body text-left">
                <h4 class="text-info">4500</h4>
                <span>Total Courses</span>
              </div>
              <div class="align-self-center w-circle-icon rounded bg-info shadow-info">
                <i class="icon-basket-loaded text-white"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-3 border-light">
          <div class="card-body">
            <div class="media">
              <div class="media-body text-left">
                <h4 class="text-danger">7850</h4>
                <span>Total Payment</span>
              </div>
              <div class="align-self-center w-circle-icon rounded bg-danger shadow-danger">
                <i class="icon-wallet text-white"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-3 border-light">
          <div class="card-body">
            <div class="media">
              <div class="media-body text-left">
                <h4 class="text-success">87.5%</h4>
                <span>Total Lecturer</span>
              </div>
              <div class="align-self-center w-circle-icon rounded bg-success shadow-success">
                <i class="icon-pie-chart text-white"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6 col-xl-3 border-light">
          <div class="card-body">
            <div class="media">
              <div class="media-body text-left">
                <h4 class="text-warning">8400</h4>
                <span>Total Students</span>
              </div>
              <div class="align-self-center w-circle-icon rounded bg-warning shadow-warning">
                <i class="icon-user text-white"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow-none border border-light">
        <div class="card-header border-0">
          Recent Activities
          <div class="card-action">
            <div class="dropdown">
              <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                <i class="icon-options"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="javascript:void();">All New Students</a>
                <a class="dropdown-item" href="javascript:void();">Admitted</a>
                <a class="dropdown-item" href="javascript:void();">Not Admitted</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void();">Separated link</a>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Photo</th>
                <th>Product ID</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Shipping</th>
              </tr>
            </thead>
            <tr>
              <td>Iphone 5</td>
              <td><img src="../assets/images/products/01.png" class="product-img" alt="product img"></td>
              <td>#9405822</td>
              <td><span class="badge gradient-quepal text-white shadow">Paid</span></td>
              <td>$ 1250.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-quepal" role="progressbar" style="width: 100%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>Earphone GL</td>
              <td><img src="../assets/images/products/02.png" class="product-img" alt="product img"></td>
              <td>#9405820</td>
              <td><span class="badge gradient-blooker text-white shadow">Pending</span></td>
              <td>$ 1500.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-blooker" role="progressbar" style="width: 60%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>HD Hand Camera</td>
              <td><img src="../assets/images/products/03.png" class="product-img" alt="product img"></td>
              <td>#9405830</td>
              <td><span class="badge gradient-bloody text-white shadow">Failed</span></td>
              <td>$ 1400.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-bloody" role="progressbar" style="width: 70%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>Clasic Shoes</td>
              <td><img src="../assets/images/products/04.png" class="product-img" alt="product img"></td>
              <td>#9405825</td>
              <td><span class="badge gradient-quepal text-white shadow">Paid</span></td>
              <td>$ 1200.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-quepal" role="progressbar" style="width: 100%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>Hand Watch</td>
              <td><img src="../assets/images/products/05.png" class="product-img" alt="product img"></td>
              <td>#9405840</td>
              <td><span class="badge gradient-bloody text-white shadow">Failed</span></td>
              <td>$ 1800.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-bloody" role="progressbar" style="width: 40%"></div>
                </div>
              </td>
            </tr>

          </table>
        </div>
      </div>
    </div>
  </div><!--End Row-->
  <!-- Admin Data Analytics ends here ---------------------------------------------- -->
<?php } ?>


<?php if ($role === 'lecturer' and $isHod === 1) { ?>
  <!-- HOD Data Analytics starts here ---------------------------------------------- -->
  <div class="row mt-3">
    <div class="col-12 col-lg-6 col-xl-3">
      <div class="card border-info border-left-sm">
        <div class="card-body">
          <div class="media">
            <div class="media-body text-left">
              <h4 class="text-info">4500</h4>
              <span>Total Courses</span>
            </div>
            <div class="align-self-center w-circle-icon rounded-circle gradient-scooter">
              <i class="icon-basket-loaded text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-6 col-xl-3">
      <div class="card border-danger border-left-sm">
        <div class="card-body">
          <div class="media">
            <div class="media-body text-left">
              <h4 class="text-danger">7850</h4>
              <span>Total Expenses</span>
            </div>
            <div class="align-self-center w-circle-icon rounded-circle gradient-bloody">
              <i class="icon-wallet text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-6 col-xl-3">
      <div class="card border-success border-left-sm">
        <div class="card-body">
          <div class="media">
            <div class="media-body text-left">
              <h4 class="text-success">87.5%</h4>
              <span>Total Lecturer</span>
            </div>
            <div class="align-self-center w-circle-icon rounded-circle gradient-quepal">
              <i class="icon-pie-chart text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-6 col-xl-3">
      <div class="card border-warning border-left-sm">
        <div class="card-body">
          <div class="media">
            <div class="media-body text-left">
              <h4 class="text-warning">8400</h4>
              <span>Total Students</span>
            </div>
            <div class="align-self-center w-circle-icon rounded-circle gradient-blooker">
              <i class="icon-user text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--End Row-->

  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow-none border border-light">
        <div class="card-header border-0">
          Recent Activities
          <div class="card-action">
            <div class="dropdown">
              <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                <i class="icon-options"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="javascript:void();">All New Students</a>
                <a class="dropdown-item" href="javascript:void();">Admitted</a>
                <a class="dropdown-item" href="javascript:void();">Not Admitted</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void();">Separated link</a>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Photo</th>
                <th>Product ID</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Shipping</th>
              </tr>
            </thead>
            <tr>
              <td>Iphone 5</td>
              <td><img src="../assets/images/products/01.png" class="product-img" alt="product img"></td>
              <td>#9405822</td>
              <td><span class="badge gradient-quepal text-white shadow">Paid</span></td>
              <td>$ 1250.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-quepal" role="progressbar" style="width: 100%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>Earphone GL</td>
              <td><img src="../assets/images/products/02.png" class="product-img" alt="product img"></td>
              <td>#9405820</td>
              <td><span class="badge gradient-blooker text-white shadow">Pending</span></td>
              <td>$ 1500.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-blooker" role="progressbar" style="width: 60%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>HD Hand Camera</td>
              <td><img src="../assets/images/products/03.png" class="product-img" alt="product img"></td>
              <td>#9405830</td>
              <td><span class="badge gradient-bloody text-white shadow">Failed</span></td>
              <td>$ 1400.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-bloody" role="progressbar" style="width: 70%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>Clasic Shoes</td>
              <td><img src="../assets/images/products/04.png" class="product-img" alt="product img"></td>
              <td>#9405825</td>
              <td><span class="badge gradient-quepal text-white shadow">Paid</span></td>
              <td>$ 1200.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-quepal" role="progressbar" style="width: 100%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>Hand Watch</td>
              <td><img src="../assets/images/products/05.png" class="product-img" alt="product img"></td>
              <td>#9405840</td>
              <td><span class="badge gradient-bloody text-white shadow">Failed</span></td>
              <td>$ 1800.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-bloody" role="progressbar" style="width: 40%"></div>
                </div>
              </td>
            </tr>

          </table>
        </div>
      </div>
    </div>
  </div><!--End Row-->
  <!-- HOD Data Analytics ends here ---------------------------------------------- -->
<?php } ?>


<?php if ($role === 'lecturer' and $role == 0) { ?>
  <!-- Lecturer Data Analytics starts here ---------------------------------------------- -->
  <div class="row mt-3">
    <div class="col-4 col-lg-6 col-xl-4">
      <div class="card border-info border-left-sm">
        <div class="card-body">
          <div class="media">
            <div class="media-body text-left">
              <h4 class="text-info">4500</h4>
              <span>No. of Courses Allocated </span>
            </div>
            <div class="align-self-center w-circle-icon rounded-circle gradient-scooter">
              <i class="icon-basket-loaded text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="col-12 col-lg-6 col-xl-3">
        <div class="card border-danger border-left-sm">
          <div class="card-body">
            <div class="media">
              <div class="media-body text-left">
                <h4 class="text-danger">7850</h4>
                <span>Total Expenses</span>
              </div>
              <div class="align-self-center w-circle-icon rounded-circle gradient-bloody">
                <i class="icon-wallet text-white"></i>
              </div>
            </div>
          </div>
        </div>
      </div> -->
    <div class="col-4 col-lg-6 col-xl-4">
      <div class="card border-success border-left-sm">
        <div class="card-body">
          <div class="media">
            <div class="media-body text-left">
              <h4 class="text-success">87.5%</h4>
              <span>Total Result Uploaded </span>
            </div>
            <div class="align-self-center w-circle-icon rounded-circle gradient-quepal">
              <i class="icon-pie-chart text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-4 col-lg-6 col-xl-4">
      <div class="card border-warning border-left-sm">
        <div class="card-body">
          <div class="media">
            <div class="media-body text-left">
              <h4 class="text-warning">8400</h4>
              <span>Total Students for my courses </span>
            </div>
            <div class="align-self-center w-circle-icon rounded-circle gradient-blooker">
              <i class="icon-user text-white"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--End Row-->

  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow-none border border-light">
        <div class="card-header border-0">
          Recent Activities
          <div class="card-action">
            <div class="dropdown">
              <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                <i class="icon-options"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="javascript:void();">All New Students</a>
                <a class="dropdown-item" href="javascript:void();">Admitted</a>
                <a class="dropdown-item" href="javascript:void();">Not Admitted</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void();">Separated link</a>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Photo</th>
                <th>Product ID</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Shipping</th>
              </tr>
            </thead>
            <tr>
              <td>Iphone 5</td>
              <td><img src="../assets/images/products/01.png" class="product-img" alt="product img"></td>
              <td>#9405822</td>
              <td><span class="badge gradient-quepal text-white shadow">Paid</span></td>
              <td>$ 1250.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-quepal" role="progressbar" style="width: 100%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>Earphone GL</td>
              <td><img src="../assets/images/products/02.png" class="product-img" alt="product img"></td>
              <td>#9405820</td>
              <td><span class="badge gradient-blooker text-white shadow">Pending</span></td>
              <td>$ 1500.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-blooker" role="progressbar" style="width: 60%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>HD Hand Camera</td>
              <td><img src="../assets/images/products/03.png" class="product-img" alt="product img"></td>
              <td>#9405830</td>
              <td><span class="badge gradient-bloody text-white shadow">Failed</span></td>
              <td>$ 1400.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-bloody" role="progressbar" style="width: 70%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>Clasic Shoes</td>
              <td><img src="../assets/images/products/04.png" class="product-img" alt="product img"></td>
              <td>#9405825</td>
              <td><span class="badge gradient-quepal text-white shadow">Paid</span></td>
              <td>$ 1200.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-quepal" role="progressbar" style="width: 100%"></div>
                </div>
              </td>
            </tr>

            <tr>
              <td>Hand Watch</td>
              <td><img src="../assets/images/products/05.png" class="product-img" alt="product img"></td>
              <td>#9405840</td>
              <td><span class="badge gradient-bloody text-white shadow">Failed</span></td>
              <td>$ 1800.00</td>
              <td>03 Aug 2017</td>
              <td>
                <div class="progress shadow" style="height: 6px;">
                  <div class="progress-bar gradient-bloody" role="progressbar" style="width: 40%"></div>
                </div>
              </td>
            </tr>

          </table>
        </div>
      </div>
    </div>
  </div><!--End Row-->
  <!-- Lecturer Data Analytics ends here ---------------------------------------------- -->
<?php } ?>


<?php include "includes/footer.php" ?>


</body>

</html>