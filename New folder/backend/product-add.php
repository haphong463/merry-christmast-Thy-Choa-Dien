<?php
require_once '../db/dbhelper.php';
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
                <form action="process/product-add-process.php" method="post" class="row" enctype="multipart/form-data">
                    <div class="form-group col-md-6">
                        <label for="cat_id">Category: </label>
                        <select name="cat_id" id="cat_id" required class="form-control">
                            <?php
                            $sql = "SELECT * FROM category";
                            $categories = executeResult($sql);
                            foreach ($categories as $c) {
                            ?>
                                <option value="<?php echo $c['cat_id'] ?>"><?php echo $c['cat_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="p_cat_id">Type: </label>
                        <select name="p_cat_id" id="p_cat_id" required class="form-control">
                            <?php
                            $sql = "SELECT * FROM product_category";
                            $type = executeResult($sql);
                            foreach ($type as $t) {
                            ?>
                                <option value="<?php echo $t['p_cat_id'] ?>"><?php echo $t['p_cat_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="name">Product Name</label>
                        <input type="text" required class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="price">Price: </label>
                        <input type="text" required class="form-control" id="price" name="price">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="keyword">Keyword: </label>
                        <input type="text" class="form-control" id="keyword" name="keyword">
                    </div>
                    <div class="form-group col-md-1">
                        <label for="size">Size:</label>
                        <div id="size-container">
                            <input type="text" maxlength="4" required class="form-control" name="size[]">

                        </div>
                        <button type="button" class="add-input">Add</button>

                    </div>

                    <div class="form-group col-md-1">
                        <label for="quantity">Quantity:</label>
                        <div id="quantity-container">
                            <input type="text" required class="form-control" name="quantity[]">
                        </div>
                    </div>


                    <div class="form-group col-md-2">
                        <label for="color">Color: </label>
                        <input type="text" required class="form-control" name="color" id="color">
                    </div>
                    <div class="form-group col-md-8">
                        <label for="desc">Description: </label>
                        <textarea id="desc" name="desc"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Product Image: </label>
                        <input type="file" required class="form-control" id="input-image" name="image[]" multiple>
                        <div id="preview-image"></div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="create-product">Add Product</button>
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