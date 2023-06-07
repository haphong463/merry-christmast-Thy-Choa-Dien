<?php
require_once '../db/dbhelper.php';
$sql = "SELECT * FROM slider";
$slider = executeResult($sql);
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
                                    <h3>Slider
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
                                    <h5>Slider</h5>
                                </div>
                                <div class="card-body">
                                    <div class="btn-popup pull-right">
                                        <a href="slider-add.php">
                                            <button type="button" class="btn btn-secondary" data-original-title="test">Add Slide</button>
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <div id="" class="product-physical">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Year</th>
                                                        <th scope="col">Heading</th>
                                                        <th scope="col">Image</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($slider != null) {
                                                        foreach ($slider as $s) {
                                                    ?>
                                                            <tr>
                                                                <td><b><?php echo $s['year'] ?></b></td>
                                                                <td><b><?php echo $s['heading']?></b></td>
                                                                <td><img src="../<?php echo $s['image'] ?>" alt="" width="200px" height="100px"></td>
                                                                <td><a href="slider-update.php?id=<?php echo $s['id'] ?>"><button class="btn btn-info">Edit</button></a> | <a href="process/category-delete.php?id=<?php echo $c['cat_id']; ?>"><button class="btn btn-danger">Delete</button></a> </td>
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