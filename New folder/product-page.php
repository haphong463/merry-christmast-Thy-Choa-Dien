<?php
require_once('db/dbhelper.php');



if (!isset($_GET['pid']) || !is_numeric($_GET['pid'])) {
    header("Location: index.php");
    exit(); //
}
$pid = $_GET['pid'];

$product_query = "SELECT * FROM product WHERE pid = $pid";
$product_result = executeSingleResult($product_query);

if (!$product_result) {
    header("Location: index.php");
    exit();
}

$p_cat_id = $product_result['p_cat_id'];

$sql_p_cat = "SELECT * FROM product_category WHERE p_cat_id = $p_cat_id";
$p_cat_result = executeSingleResult($sql_p_cat);
?>

<?php include('layout/header.php') ?>

<!-- Page Add Section Begin -->
<section class="page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2><?php echo $p_cat_result['p_cat_name'] ?><span>.</span></h2>
                    <a href="index.php">Home</a>
                    <a href="categories.php?p_cat_id=<?php echo $p_cat_id ?>"><?php echo $p_cat_result['p_cat_name'] ?></a>
                </div>
            </div>
            <?php
            include('layout/discount.php');
            ?>
        </div>
    </div>
</section>
<!-- Page Add Section End -->

<!-- Product Page Section Beign -->
<section class="product-page">
    <div class="container">
        <?php
        $sql = "SELECT * FROM product";
        $products = executeResult($sql);
        $current_pid = isset($_GET['pid']) ? $_GET['pid'] : null; // Lấy pid hiện tại

        $previous_pid = null; // Pid của sản phẩm trước đó
        $next_pid = null; // Pid của sản phẩm tiếp theo

        // Tìm vị trí của pid hiện tại trong danh sách sản phẩm
        $current_product_index = -1;
        foreach ($products as $index => $product) {
            if ($product['pid'] == $current_pid) {
                $current_product_index = $index;
                break;
            }
        }

        // Lấy pid của sản phẩm trước đó và tiếp theo
        if ($current_product_index !== -1) {
            $previous_pid = ($current_product_index > 0) ? $products[$current_product_index - 1]['pid'] : null;
            $next_pid = ($current_product_index < count($products) - 1) ? $products[$current_product_index + 1]['pid'] : null;
        }
        ?>
        <div class="product-control">
            <?php if ($previous_pid !== null) { ?>
                <a href="product-page.php?pid=<?php echo $previous_pid ?>">Previous</a>
            <?php } ?>
            <?php if ($next_pid !== null) { ?>
                <a href="product-page.php?pid=<?php echo $next_pid ?>">Next</a>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="product-slider owl-carousel">
                    <?php

                    $sqlCount = "SELECT COUNT(*) AS totalImages FROM Product_Image WHERE pid = $pid";
                    $resultCount = executeSingleResult($sqlCount);

                    if ($resultCount != null) {
                        $totalImages = $resultCount['totalImages']; // Tổng số hình ảnh
                    } else {
                        $totalImages = 0;
                    }
                    // Câu truy vấn SQL để lấy các hình ảnh sản phẩm
                    $sql = "SELECT image_path FROM Product_Image WHERE pid = $pid LIMIT 7";
                    $result = executeResult($sql);

                    if ($result != null) {
                        // Duyệt qua từng dòng dữ liệu
                        $count = 1;
                        foreach ($result as $r) {
                            $imagePath = $r['image_path'];
                            $statusText = ($count <= $totalImages) ? "$count/$totalImages" : ""; // Hiển thị "2/3" khi $count = 2
                    ?>
                            <div class="product-img">
                                <figure>
                                    <img src="<?php echo $imagePath; ?>" width="300px" height="500px" alt="">
                                    <div class="p-status"><?php echo $statusText ?></div>
                                </figure>
                            </div>
                    <?php
                            $count++;
                        }
                    } else {
                    }
                    ?>
                </div>
            </div>


            <!-- <?php
                    $sql = "SELECT * FROM PRODUCT where pid = $pid";
                    $info_product = executeSingleResult($sql);
                    $sql_cat = "SELECT * FROM category";
                    $result = executeResult($sql_cat);
                    $sql_p_cat = "SELECT * FROM product_category";
                    $result_p_cat = executeResult($sql_p_cat);
                    $sql_variant = "SELECT * FROM product_variant WHERE pid = $pid GROUP BY keyword";
                    $result_variant = executeResult($sql_variant);
                    ?> -->
            <div class="col-lg-6">
                <div class="product-content">
                    <small>

                        <?php
                        $createdDate = $product_result['created_at'];
                        $currentDate = date('Y-m-d');
                        $dateDiff = floor((strtotime($currentDate) - strtotime($createdDate)) / (60 * 60 * 24));

                        if ($dateDiff < 7) {
                            echo '#NEW';
                        }
                        ?>
                    </small>
                    <h2><?php echo $info_product['name'] ?></h2>
                    <div class="pc-meta">
                        <h5>
                            <div class="price">
                                <span>$<?php echo number_format($product_result['price'], 2, '.', '.') ?> </span>
                            </div>
                        </h5>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                    </div>
                    <?php
                    $product_quantity_query = "SELECT size FROM product_variant WHERE pid = $pid";
                    $size_result = executeResult($product_quantity_query);

                    ?>

                    <form action="shopping-cart.php" method="post">
                        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                        <input type="hidden" name="color" value="<?php echo $product_result['color'] ?>">
                        <div class="form-group">
                            <!-- form-group Begin -->
                            <div class='pd-size-choose'>
                                <span id="quantities"></span>
                                <?php foreach ($size_result as $size) {
                                    $firstSize = $size_result[0]['size'];
                                    $value = '';
                                    if ($size['size'] == "M") {
                                        $value = "Medium";
                                    } elseif ($size['size'] == "S") {
                                        $value = "Small";
                                    } elseif ($size['size'] == "L") {
                                        $value = "Large";
                                    } else if ($size['size'] == "XL") {
                                        $value = "Extra Large";
                                    }

                                    $product_quantity_query = "SELECT * FROM product_variant WHERE pid = $pid AND size = '{$size['size']}'";
                                    $product_quantity_result = executeSingleResult($product_quantity_query);
                                    $quantity = $product_quantity_result['quantity'];

                                    if ($quantity > 0) { // Kiểm tra quantity
                                ?>
                                        <div class='sc-item' data-quantity=<?php echo $quantity ?>>

                                            <input type='radio' id='<?php echo $size['size'] ?>-size' class="form-control" name='size' value="<?php echo $value ?>" <?php if ($size['size'] == $firstSize) {
                                                                                                                                                                        echo "checked";
                                                                                                                                                                    } ?> required novalidate>
                                            <label for='<?php echo $size['size'] ?>-size'><?php echo $size['size'] ?></label>
                                        </div>
                                    <?php } else { ?>
                                        <div class='sc-item out-of-stock'>
                                            <input type='radio' id='<?php echo $size['size'] ?>-size' class="form-control" name='size' value="<?php echo $value ?>" disabled>
                                            <label for='<?php echo $size['size'] ?>-size'><?php echo $size['size'] ?><span class="stock-status">X</span></label>
                                        </div>
                                <?php }
                                } ?>
                            </div>
                        </div>

                        <div class="pro-quantity">
                            <button type="button" class="quantity" onclick="decrement()">-</button>
                            <input type="number" id="quantity" name="quantity" readonly min="1" value="1">
                            <button type="button" class="quantity" onclick="increment()">+</button>
                        </div>
                        <?php
                        if (isset($_SESSION['user'])) {
                        ?>
                            <button name="add-to-cart" id="add" class="add">Add to cart</button>
                        <?php
                        } else {
                        ?>
                            <button class="add"><a href="signin.php">Add to cart</a></button>
                        <?php
                        }
                        ?>
                    </form>
                    <ul class="tags">

                        <li><span>Category: </span>
                            <?php

                            $category = "";
                            foreach ($result as $cat) {
                                if ($cat['cat_id'] == $info_product['cat_id']) {
                                    $category .= $cat['cat_name'];
                                }
                            }
                            foreach ($result_p_cat as $r) {
                                if ($r['p_cat_id'] == $info_product['p_cat_id']) {
                                    if ($category !== "") {
                                        $category .= ", ";
                                    }
                                    $category .= $r['p_cat_name'];
                                }
                            }
                            echo $category;
                            ?>
                        </li>

                        <li><span>Tags :</span> <?php
                                                foreach ($result_variant as $v) {
                                                    if ($v['pid'] == $info_product['pid']) {
                                                        echo $v['keyword'];
                                                    }
                                                }
                                                ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container mt-5">
    <div class="product-tabs">
        <button class="tab-button active" data-tab="description">
            Description
        </button>
        <button class="tab-button" data-tab="review">Review</button>
    </div>

    <div class="tab-content active" id="description">
        <?php
        $description = $product_result['description'];
        $description = str_replace('<ul>', '<ul style="color: #838383; font-size: 14px; font-weight: 500; line-height: 30px; margin-bottom: 35px; margin-left:15px">', $description);
        echo $description;
        ?>
    </div>

    <div class="tab-content" id="review">

    </div>
</div>
<!-- Product Page Section End -->

<!-- Related Product Section Begin -->
<section class="related-product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <?php
                $related_product_query = "SELECT * FROM product WHERE p_cat_id = $p_cat_id AND pid != $pid LIMIT 4";
                $related_product_result = executeResult($related_product_query);

                if (count($related_product_result) > 0) : ?>
                    <h2 style="font-weight: 700">Related Products</h2>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <?php
            foreach ($related_product_result as $related_product) {
                $related_product_id = $related_product['pid'];

                // Lấy thông tin hình ảnh từ bảng product_image
                $image_query = "SELECT image_path FROM product_image WHERE pid = $related_product_id LIMIT 1";
                $image_result = executeSingleResult($image_query);

                // Kiểm tra xem có hình ảnh liên quan không
                if ($image_result) {
                    $related_product_image = $image_result['image_path'];
                } else {
                    $related_product_image = 'default_image.jpg'; // Hình ảnh mặc định nếu không có hình ảnh liên quan
                }
            ?>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-product-item">
                        <figure>
                            <a href="product-page.php?pid=<?php echo $related_product_id ?>"><img src="<?php echo $related_product_image ?>" height="300px" alt=""></a>
                            <div class="p-status">NEW</div>
                        </figure>
                        <div class="product-text">
                            <h6><?php echo $related_product['name'] ?></h6>
                            <p>$<?php echo $related_product['price'] ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
<!-- Related Product Section End -->

<!-- Footer Section Begin -->
<script>
    // Lấy tất cả các phần tử input radio của size
    var sizeInputs = document.querySelectorAll('input[name=size]');

    // Tìm size được chọn ban đầu
    var selectedSize = document.querySelector('input[name=size]:checked');

    // Nếu có size được chọn ban đầu
    if (selectedSize) {
        // Lấy số lượng từ thuộc tính data-quantity của phần tử cha
        var quantity = selectedSize.parentNode.getAttribute('data-quantity');

        // Hiển thị số lượng
        var quantityElement = document.getElementById('quantities');
        quantityElement.innerText = "Quantity: " + quantity;
    }

    // Lặp qua từng phần tử và thêm sự kiện change
    sizeInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            // Kiểm tra xem size nào được chọn
            if (input.checked) {
                // Lấy số lượng từ thuộc tính data-quantity của phần tử cha
                var quantity = input.parentNode.getAttribute('data-quantity');

                // Hiển thị số lượng
                var quantityElement = document.getElementById('quantities');
                quantityElement.innerText = "Quantity: " + quantity;
            }
        });
    });
</script>



<script>
    function validateSizeSelection() {
        var sizes = document.getElementsByName('size');
        var sizeSelected = false;

        for (var i = 0; i < sizes.length; i++) {
            if (sizes[i].checked) {
                sizeSelected = true;
                break;
            }
        }

        var errorMessage = document.getElementById('size-error-message');
        if (sizeSelected && errorMessage) {
            errorMessage.remove();
        } else if (!sizeSelected && !errorMessage) {
            errorMessage = document.createElement('p');
            errorMessage.innerHTML = 'Please choose a size!';
            errorMessage.style.color = 'red';
            errorMessage.id = 'size-error-message';

            var sizeChooseDiv = document.querySelector('.pd-size-choose');
            sizeChooseDiv.appendChild(errorMessage);
        }
    }
    var sizeInputs = document.getElementsByName('size');
    for (var i = 0; i < sizeInputs.length; i++) {
        sizeInputs[i].addEventListener('change', validateSizeSelection);
    }

    var addButton = document.getElementById('add');
    addButton.addEventListener('click', validateSizeSelection);
</script>
<script>
    function decrement() {
        var quantityInput = document.getElementById('quantity');
        var currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }

    function increment() {
        var quantityInput = document.getElementById('quantity');
        var currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
    }
</script>

<script>
    // Lắng nghe sự kiện khi người dùng click vào nút hoặc tab
    var tabButtons = document.querySelectorAll('.tab-button');
    var tabContents = document.querySelectorAll('.tab-content');

    function setActiveTab(tab) {
        // Xóa lớp active khỏi tất cả các nút hoặc tab
        tabButtons.forEach(function(button) {
            button.classList.remove('active');
        });

        // Ẩn tất cả các nội dung của tab
        tabContents.forEach(function(content) {
            content.classList.remove('active');
        });

        // Thêm lớp active cho nút hoặc tab được chọn
        tab.classList.add('active');

        // Hiển thị nội dung của tab tương ứng
        var targetTabId = tab.getAttribute('data-tab');
        var targetTabContent = document.getElementById(targetTabId);
        targetTabContent.classList.add('active');
    }

    // Lắng nghe sự kiện click cho mỗi nút hoặc tab
    tabButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            setActiveTab(this);
        });
    });

    // Mặc định hiển thị tab Description khi trang được tải
    setActiveTab(tabButtons[0]);
</script>
<?php include('layout/footer.php') ?>