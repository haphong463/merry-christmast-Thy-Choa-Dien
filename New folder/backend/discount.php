<?php
require_once '../db/dbhelper.php';
$sql = "SELECT * FROM discount";
$discount = executeResult($sql);
?>

<?php include('part/header.php') ?>


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
                                    <h3>Discount
                                        <small>La Mode Parisienne</small>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ol class="breadcrumb pull-right">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Digital</li>
                                    <li class="breadcrumb-item active">Category</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->

                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Discount</h5>
                                </div>

                                <div class="card-body">
                                    <div class="btn-popup pull-right">
                                        <a href="discount-add.php">
                                            <button type="button" class="btn btn-secondary" data-original-title="test">Add Coupon</button>
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <div id="" class="product-physical">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Count</th>
                                                        <th scope="col">Coupon Code</th>
                                                        <th scope="col">Discount</th>
                                                        <th scope="col">Quantity</th>
                                                        <th scope="col">Banner</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($discount != null) {
                                                        foreach ($discount as $d) {
                                                    ?>
                                                            <tr>
                                                                <td>
                                                                    <span style="font-weight: bold;" id="countdown-<?php echo $d['id']; ?>"></span>
                                                                    <script>
                                                                        // Đếm ngược thời gian
                                                                        var countdownDate<?php echo $d['id']; ?> = new Date("<?php echo  $d['expiration_date']; ?>").getTime();
                                                                        var countdownElement<?php echo $d['id']; ?> = document.getElementById("countdown-<?php echo $d['id']; ?>");

                                                                        var countdownTimer<?php echo $d['id']; ?> = setInterval(function() {
                                                                            var now = new Date().getTime();
                                                                            var distance = countdownDate<?php echo $d['id']; ?> - now;

                                                                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                                                            countdownElement<?php echo $d['id']; ?>.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

                                                                            if (distance < 0) {
                                                                                clearInterval(countdownTimer<?php echo $d['id']; ?>);
                                                                                countdownElement<?php echo $d['id']; ?>.innerHTML = "Expired";
                                                                            }
                                                                        }, 1000);
                                                                    </script>
                                                                </td>
                                                                <td><?php echo $d['coupon_code'] ?></td>
                                                                <td><?php echo number_format($d['discount'], 0) ?>%</td>
                                                                <td><?php echo $d['quantity'] ?></td>
                                                                <td><img src="../<?php echo $d['banner']; ?>" width="200px" height="150px" alt=""></td>
                                                                <td><a href="discount-edit.php?d_id=<?php echo $d['id'] ?>"><button class="btn btn-info">Edit</button></a> | <a href="process/delete.php?id=<?php echo $d['id'] ?>"><button class="btn btn-danger">Delete</button></a> </td>

                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->

            </div>

            <!-- footer start-->
            <?php include('part/footer.php') ?>