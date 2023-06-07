<?php
require_once '../db/dbhelper.php';
$sql = "SELECT * FROM product";
$product = executeResult($sql);

$sql_category = "SELECT * FROM category";
$categories = executeResult($sql_category);

$sql_type = "SELECT * FROM product_category";
$types = executeResult($sql_type);

$sql_variant = "SELECT * FROM product_variant";
$variant = executeResult($sql_variant);

$sql_image = "SELECT * FROM product_image";
$images = executeResult($sql_image);

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
                                    <h3>Product List
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
                                    <h5>Digital Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="btn-popup pull-right">
                                        <a href="product-add.php">
                                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-original-title="test" data-target="#exampleModal">Add Product</button>
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <div id="" class="product-physical">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Category</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Product Name</th>
                                                        <th scope="col">Price</th>
                                                        <th scope="col">Color</th>
                                                        <th scope="col">Size</th>
                                                        <th scope="col">Quantity</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($product != null) {
                                                        foreach ($product as $p) {
                                                            // $quantity_string = '';
                                                            $pid = $p['pid'];
                                                            $quantityDisplayed = false;

                                                            $sql_variant = "SELECT quantity, size FROM product_variant where pid = $pid";
                                                            $variant = executeResult($sql_variant);

                                                            // foreach ($quantity_result as $quantity) {
                                                            //     $quantity_array[] = $quantity['quantity'];
                                                            // }

                                                            // if (!$quantityDisplayed) {
                                                            //     $quantity_string .= implode(' | ', $quantity_array);
                                                            //     $quantityDisplayed = true;
                                                            // }
                                                    ?>
                                                            <tr>
                                                                <?php
                                                                foreach ($categories as $c) {
                                                                    if ($c['cat_id'] == $p['cat_id']) {
                                                                ?>
                                                                        <td><b><?php echo $c['cat_name']  ?></b></td>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <?php
                                                                foreach ($types as $t) {
                                                                    if ($t['p_cat_id'] == $p['p_cat_id']) {
                                                                ?>
                                                                        <td><b><?php echo $t['p_cat_name']  ?><b></td>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>

                                                                <td><?php echo $p['name'] ?></td>
                                                                <td><?php echo $p['price'] ?></td>
                                                                <td><?php echo $p['color'] ?></td>
                                                                <td><?php
                                                                    $sizeCount = count($variant);
                                                                    foreach ($variant as $index => $size) {
                                                                        echo $size['size'];
                                                                        if ($index < $sizeCount - 1) {
                                                                            echo ' | ';
                                                                        }
                                                                    }
                                                                    ?></td>
                                                                <td><?php $quantityCount = count($variant);
                                                                    foreach ($variant as $index => $quantity) {
                                                                        echo $quantity['quantity'];
                                                                        if ($index < $quantityCount - 1) {
                                                                            echo ' | ';
                                                                        }
                                                                    } ?></td>
                                                                <td><a href="product-update.php?pid=<?php echo $p['pid'] ?>">
                                                                        <button class="btn btn-info">
                                                                            Edit
                                                                        </button>
                                                                    </a>
                                                                    |
                                                                    <a href="process/category-delete.php?id=<?php echo $p['pid']; ?>">
                                                                        <button class="btn btn-danger">
                                                                            Delete
                                                                        </button>
                                                                    </a>
                                                                </td>


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

            <?php include('part/footer.php') ?>