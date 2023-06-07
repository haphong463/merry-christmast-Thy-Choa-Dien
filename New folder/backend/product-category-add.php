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
                                    <h3>NEW ONE CATEGORY
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
                <form action="process/product-category-add-process.php" method="post">
                    <div class="form-group">
                        <label for="name">Product Category Name: </label>
                        <input type="text" class="form-control" id="name" name="name" req placeholder="dresses, t-shirt, jacket, ...">
                    </div>
                    <div class="form-group">
                        <label for="description">Product Category Description: </label>
                        <input type="text" class="form-control" name="description" id="description" placeholder="description here..">
                    </div>
                    <button type="submit" class="btn btn-primary" name="create-pro-cat">Add Product Category</button>
                </form>
                <!-- Container-fluid Ends-->

            </div>

            <!-- footer start-->
            <?php include('part/footer.php') ?>