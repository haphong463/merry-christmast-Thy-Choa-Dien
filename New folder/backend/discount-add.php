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
                <form action="process/discount-add-process.php" class="row" enctype="multipart/form-data" method="post">
                    <div class="form-group col-md-6">
                        <label for="discount">Discount: </label>
                        <input type="text" class="form-control" name="discount" required id="discount" pattern="^(?:100|[1-9]\d|\d)%?$" placeholder="1-100 (%)">
                    </div>
                    <div class="form-grou col-md-6">
                        <label for="date">Expiration Date: </label>
                        <input type="number" class="form-control" name="date" required id="date" placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="quantity">Quantity: </label>
                        <input type="number" class="form-control" name="quantity" id="quantity">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="banner">Banner: </label>
                        <input type="file" class="form-control" name="banner" id="banner">
                    </div>
                    <button type="submit" class="btn btn-primary" name="create-coupon">Add Coupon</button>
                </form>
                <!-- Container-fluid Ends-->

            </div>

            <!-- footer start-->
            <?php include('part/footer.php') ?>