<?php
require_once '../db/dbhelper.php';
if (isset($_GET['p_cat_id'])) {
    $id = $_GET['p_cat_id'];
    $sql = "SELECT * FROM product_category where p_cat_id = $id";
    $category = executeSingleResult($sql);
    $name = $category['p_cat_name'];
    $description = $category['p_cat_desc'];
}

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
                                    <h3>Product Category
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
                <form action="process/product-category-update-process.php" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" id="id" name="p_cat_id" hidden value="<?php echo $id ?>">
                    </div>
                    <div class="form-group">
                        <label for="name">Type Name: </label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $name ?>" required placeholder="men, women, old, children...">
                    </div>
                    <div class="form-group">
                        <label for="description">Product Category Description: </label>
                        <input type="text" class="form-control" name="description" value="<?php echo $description ?>" id="description" required placeholder="description here..">
                    </div>
                    <button type="submit" class="btn btn-primary" name="update-pro-cat">Update Category</button>
                </form>
                <!-- Container-fluid Ends-->

            </div>

            <!-- footer start-->
            <?php include('part/footer.php') ?>