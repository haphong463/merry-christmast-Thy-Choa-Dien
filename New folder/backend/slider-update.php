<?php
require_once('../db/dbhelper.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM slider where id = $id";
    $info = executeSingleResult($sql);
    $year = $info['year'];
    $heading = $info['heading'];
    $image = $info['image'];
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
                <form action="process/slide-update-process.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <div class="form-group">
                        <label for="year">Year: </label>
                        <input type="text" class="form-control" value="<?php echo $year ?>" id="year" required name="year">
                    </div>
                    <div class="form-group">
                        <label for="heading">Heading: </label>
                        <input type="text" class="form-control" value="<?php echo $heading ?>" required name="heading" id="heading" placeholder="enter heading here...">
                    </div>
                    <div class="form-group">
                        <label for="slide">Image: </label>
                        <input type="file" class="form-control" name="slide" id="slide">
                        <img src="../<?php echo $image ?>" alt="" width="1440px" height="670px">
                    </div>
                    <button type="submit" class="btn btn-primary" name="update-slide">Update Slide</button>
                </form>
                <!-- Container-fluid Ends-->

            </div>

            <!-- footer start-->
            <?php include('part/footer.php') ?>