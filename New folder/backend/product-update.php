<?php
require_once '../db/dbhelper.php';

if (isset($_GET['pid'])) {
    $id = $_GET['pid'];
    $products = "SELECT * FROM product where pid = $id";
    $result = executeSingleResult($products);

    if (!$result) {
        header('Location: index.php');
    }
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
                                    <h3>NEW ONE PRODUCT
                                        <small>La Mode Parisienne</small>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ol class="breadcrumb pull-right">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Digital</li>
                                    <li class="breadcrumb-item active">Product</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->

                <!-- Container-fluid starts-->

                <form action="process/product-update-process.php" method="post" class="row" enctype="multipart/form-data">
                    <input type="hidden" name="pid" value="<?php echo $id ?>">
                    <div class="form-group col-md-6">
                        <label for="">Category: </label>
                        <select name="cat_id" id="" required class="form-control">
                            <?php
                            $sql = "SELECT * FROM category";
                            $categories = executeResult($sql);
                            foreach ($categories as $c) {

                            ?>
                                <?php $selected = ($result && $result['cat_id'] == $c['cat_id']) ? 'selected' : '' ?>
                                <option value="<?php echo $c['cat_id'] ?>" <?php echo $selected ?>><?php echo $c['cat_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="image">Type: </label>
                        <select name="p_cat_id" id="" required class="form-control">
                            <?php
                            $sql = "SELECT * FROM product_category";
                            $type = executeResult($sql);
                            foreach ($type as $t) {
                            ?>
                                <?php $selected = ($result && $result['p_cat_id'] == $t['p_cat_id']) ? 'selected' : '' ?>
                                <option value="<?php echo $t['p_cat_id'] ?>" <?php echo $selected ?>><?php echo $t['p_cat_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="name">Product Name</label>
                        <input type="text" value="<?php echo $result['name'] ?>" required class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="price">Price: </label>
                        <input type="text" value="<?php echo $result['price'] ?>" required class="form-control" id="price" name="price">
                    </div>
                    <?php
                    $sql_variant = "SELECT keyword FROM product_variant where pid = $id";
                    $variants = executeSingleResult($sql_variant);
                    ?>
                    <div class="form-group col-md-4">
                        <label for="keyword">Keyword: </label>
                        <input type="text" value="<?php echo $variants['keyword'] ?>" class="form-control" id="keyword" name="keyword">
                    </div>
                    <?php
                    $sql_variant = "SELECT quantity, size FROM product_variant WHERE pid = $id";

                    $variant = executeResult($sql_variant);

                    $quantity_string = '';

                    $quantity_array = array();
                    foreach ($variant as $quantity) {
                        $quantity_array[] = $quantity['quantity'];
                    }
                    $quantity_string .= implode(', ', $quantity_array);

                    ?>
                    <div class="form-group col-md-1">
                        <label for="size">Size:</label>
                        <?php
                        foreach ($variant as $size) {
                            echo '<div id="size-container">
                <input type="text" required class="form-control" value="' . $size['size'] . '" name="size[]">
              </div>';
                        }
                        ?>
                        <button type="button" class="add-input">Add</button>
                    </div>




                    <div class="form-group col-md-1">
                        <label for="quantity">Quantity:</label>
                        <div id="quantity-container">
                            <?php
                            foreach ($variant as $quantity) {
                                echo '
                                
                                <input type="text" class="form-control" value="' . $quantity['quantity'] . '" name="quantity[]">


                                ';
                            }
                            ?>
                        </div>
                    </div>




                    <div class="form-group col-md-4">
                        <label for="color">Color: </label>
                        <input type="text" value="<?php echo $result['color'] ?>" required class="form-control" name="color" id="color">
                    </div>
                    <div class="form-group">
                        <label for="desc">Description: </label>
                        <textarea id="desc" name="desc"><?php echo $result['description'] ?></textarea>
                    </div>
                    <div class="form-group ml-">
                        <label for="image">Product Image: </label>
                        <input type="file" class="form-control" id="image" name="image[]" multiple>
                        <div class="row">
                            <?php
                            $products_image = "SELECT * FROM product_image where pid = $id";
                            $result_image = executeResult($products_image);
                            $count = 0;
                            foreach ($result_image as $r) {
                                if ($count % 3 == 0) {
                                    echo '</div><div class="row">';
                                }
                            ?>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <img src="../<?php echo $r['image_path'] ?>" alt="Product Image" class="img-fluid">
                                </div>
                            <?php
                                $count++;
                            }
                            ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="update-product">Update Product</button>
                </form>
                <!-- Container-fluid Ends-->

            </div>
            <script>
                // Lấy các phần tử chứa input
                const sizeContainer = document.getElementById('size-container');
                const quantityContainer = document.getElementById('quantity-container');

                // Lấy nút "Add"
                const addInputBtn = document.querySelector('.add-input');

                // Gắn sự kiện click cho nút "Add"
                addInputBtn.addEventListener('click', function() {
                    // Tạo các phần tử mới
                    const newSizeInput = document.createElement('input');
                    newSizeInput.type = 'text';
                    newSizeInput.required = true;
                    newSizeInput.maxLength = 4; // Thêm maxlength = 3
                    newSizeInput.classList.add('form-control');
                    newSizeInput.name = 'size[]';

                    const newQuantityInput = document.createElement('input');
                    newQuantityInput.type = 'text';
                    newQuantityInput.required = true;
                    newQuantityInput.classList.add('form-control');
                    newQuantityInput.name = 'quantity[]';

                    // Thêm các phần tử mới vào container tương ứng
                    sizeContainer.appendChild(newSizeInput);
                    quantityContainer.appendChild(newQuantityInput);
                });
            </script>

            <script>
                tinymce.init({
                    selector: 'textarea#desc',
                    plugins: 'lists',
                    toolbar: 'undo redo | blocks fontsize | bold italic underline | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap',
                });
            </script>
            <?php include('part/footer.php') ?>