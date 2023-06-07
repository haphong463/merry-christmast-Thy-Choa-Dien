<?php include('part/header.php') ?>
<?php
require_once('../db/dbhelper.php');
if (isset($_GET['d_id'])) {
    $id = $_GET['d_id'];
    $sql = "SELECT * FROM discount WHERE id = $id ";
    $run_sql = executeSingleResult($sql);
    $expirationDate = $run_sql['expiration_date'];
    $discount = number_format($run_sql['discount'], 0) . '%';


    $startDate = $run_sql['startDate']; // Thay thế giá trị này bằng cách lấy từ cơ sở dữ liệu
    $image = $run_sql['banner'];
    // Tính toán số ngày còn lại
    $remainingDays = intval((strtotime($expirationDate) - strtotime($startDate)) / (60 * 60 * 24));
}

?>

<body>


    <!-- page-wrapper Start-->
    <div class="page-wrapper">

        <!-- Page Header Start-->
        <?php include('part/headerBackend.php'); ?>
        <!-- Page Header Ends -->

        <!-- Page Body Start-->
        <div class="page-body-wrapper">

            <!-- Page Sidebar Start-->
            <?php include('part/menu-left.php'); ?>
            <!-- Page Sidebar Ends-->

            <!-- Right sidebar Start-->

            <!-- Right sidebar Ends-->

            <div class="page-body">

                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="page-header-left">
                                    <h3>NEW ONE COUPON
                                        <small>La Mode Parisienne</small>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ol class="breadcrumb pull-right">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Digital</li>
                                    <li class="breadcrumb-item active">Coupon</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->

                <!-- Container-fluid starts-->
                <form action="process/discount-edit-process.php" class="row" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <div class="form-group col-md-6">
                        <label for="discount">Discount: </label>
                        <input type="text" class="form-control" name="discount" value="<?php echo $discount; ?>" required id="discount" pattern="^(?:100|[1-9]\d|\d)%?$" placeholder="1-100 (%)">
                    </div>
                    <div class="form-grou col-md-6">
                        <label for="date">Expiration Date: </label>
                        <input type="number" class="form-control" name="date" value="<?php echo $remainingDays;
                                                                                        ?>" required id="date" placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="banner">Banner: </label>
                        <input type="file" class="form-control" name="banner" id="banner">
                    </div>
                    <img src="../<?php echo $image ?>" alt="">
                    <button type="submit" class="btn btn-primary" name="update-coupon">Update Coupon</button>
                </form>
                <!-- Container-fluid Ends-->

            </div>

            <!-- footer start-->
            <?php include('part/footer.php') ?>