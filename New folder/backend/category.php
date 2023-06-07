<?php
require_once '../db/dbhelper.php';
$sql = "SELECT * FROM category";
$category = executeResult($sql);
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
                                    <h3>Category
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
                                    <h5>Categories</h5>
                                </div>
                                <div class="card-body">
                                    <div class="btn-popup pull-right">
                                        <a href="category-add.php">
                                            <button type="button" class="btn btn-secondary" data-original-title="test">Add Category</button>
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <div id="" class="product-physical">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Description</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($category != null) {
                                                        foreach ($category as $c) {
                                                    ?>
                                                            <tr>
                                                                <td><b><?php echo $c['cat_name'] ?></b></td>
                                                                <td width="70%"><?php echo $c['cat_desc'] ?></td>
                                                                <td><a href="category-update.php?cat_id=<?php echo $c['cat_id'] ?>"><button class="btn btn-info">Edit</button></a> | <a href="process/category-delete.php?id=<?php echo $c['cat_id']; ?>"><button class="btn btn-danger">Delete</button></a> </td>
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