<?php
include('layout/header.php');

require_once 'db/dbhelper.php';
// Thêm sản phẩm vào giỏ hàng
if (isset($_POST['add-to-cart'])) {
    $pid = $_POST['pid'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];

    $sql = "SELECT * FROM cart WHERE c_id = '$email'";
    $existingProduct = executeResult($sql);
    $_SESSION['discount'] = 0;

    if ($existingProduct != NULL) {
        foreach ($existingProduct as $exist) {
            if ($exist['size'] === $size && $exist['pid'] == $pid) {
                echo '<script>alert("Product already exists"); window.location.href = "product-page.php?pid=' . $pid . '";</script>';
                exit();
            }
        }
    }

    $shortSize = '';
    switch ($size) {
        case "Large":
            $shortSize = "L";
            break;
        case "Medium":
            $shortSize = "M";
            break;
        case "Extra Large":
            $shortSize = "XL";
            break;
        case "Small":
            $shortSize = "S";
            break;
    }
    $product_quantity_query = "SELECT * FROM product_variant WHERE pid = $pid AND size = '$shortSize'";
    $product_quantity_result = executeSingleResult($product_quantity_query);
    $quantity_available = $product_quantity_result['quantity'];
    if ($quantity > $quantity_available) {
        // Hiển thị thông báo lỗi
        echo '<script>alert("The requested quantity exceeds the available quantity. Please reduce the quantity."); window.location.href = "product-page.php?pid=' . $pid . '";</script>';
        exit();
    }

    $sql = "INSERT INTO cart (c_id, pid, quantity, size, date) VALUES ('$email', '$pid', '$quantity', '$size', NOW())";
    execute($sql);


    echo '<script>window.location.href = "shopping-cart.php";</script>';
    exit();
}

// Lấy thông tin giỏ hàng
$sql = "SELECT cart.*, product.name, product.price, product_thumbnail.thumbnail 
    FROM cart  INNER JOIN product ON cart.pid = product.pid
    INNER JOIN (
        SELECT MIN(id) AS minID, thumbnail, pid
        FROM product_thumbnail
        GROUP BY pid
    ) AS product_thumbnail ON product.pid = product_thumbnail.pid
    WHERE cart.c_id = '$email'";
$cartItems = executeResult($sql);


$totalCart = 0;
$total = 0;

// Xóa giỏ hàng
if (isset($_POST['clear-cart'])) {
    $sql = "DELETE FROM cart WHERE c_id = '$email'";
    execute($sql);

    $_SESSION['discount'] = 0;
    echo '<script>window.location.href = "shopping-cart.php";</script>';
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM cart WHERE id = $id";
    execute($sql);
    echo '<script>window.location.href = "shopping-cart.php";</script>';
    exit();
}

if (isset($_POST['update'])) {
    $quantities = $_POST['quantity'];
    foreach ($cartItems as $key => $cartItem) {
        $quantity = $quantities[$key];
        $cartItems[$key]['quantity'] = $quantity;

        $pid = $cartItem['pid'];
        $size = $cartItem['size'];
        $shortSize = '';
        switch ($size) {
            case "Large":
                $shortSize = "L";
                break;
            case "Medium":
                $shortSize = "M";
                break;
            case "Extra Large":
                $shortSize = "XL";
                break;
            case "Small":
                $shortSize = "S";
                break;
        }
        $product_quantity_query = "SELECT * FROM product_variant WHERE pid = $pid AND size = '$shortSize'";
        $product_quantity_result = executeSingleResult($product_quantity_query);
        $quantity_available = $product_quantity_result['quantity'];

        if ($quantity > $quantity_available) {
            echo '<script>alert("The requested quantity exceeds the available quantity. Please reduce the quantity."); window.location.href = "shopping-cart.php";</script>';
            exit();
        }

        $subtotal = $cartItem['price'] * $quantity;
        $totalCart += $subtotal;

        $cartId = $cartItem['id'];
        $sql = "UPDATE cart SET quantity = $quantity WHERE id = $cartId";
        execute($sql);
    }

    $couponCode = isset($_POST['coupon_code']) ? $_POST['coupon_code'] : '';
    $_SESSION['coupon_code'] = $couponCode;

    if (!empty($couponCode)) {
        $sql = "SELECT * FROM discount WHERE coupon_code = '$couponCode'";
        $coupon = executeSingleResult($sql);
        if ($coupon) {
            $expirationDate = $coupon['expiration_date'];
            $currentDate = date('Y-m-d');
            if ($currentDate > $expirationDate) {
                echo '<script>alert("Coupon has expired")</script>';
            } else {
                $discountPercentage = $coupon['discount'];
                $discountAmount = $totalCart * ($discountPercentage / 100);
                $_SESSION['discount'] = $discountAmount;
                $total = $totalCart - $discountAmount;
            }
        } else {
            echo '<script>alert("Invalid coupon code");</script>';
        }
    } else {
        $_SESSION['discount'] = 0;
    }

    echo '<script>window.location.href = "shopping-cart.php";</script>';
    exit();
}
?>

<!-- Header Info End -->
<!-- Header End -->

<!-- Page Add Section Begin -->
<section class="page-add cart-page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2>Cart<span>.</span></h2>
                    <a href="index.php">Home</a>
                    <a href="shopping-cart.php">Cart</a>
                </div>
            </div>
            <?php
            include('layout/discount.php');
            ?>
        </div>
    </div>
</section>
<!-- Page Add Section End -->

<!-- Cart Page Section Begin -->
<div class="cart-page">
    <div class="container">
        <form action="shopping-cart.php" method="post">
            <div class="cart-table">
                <table>
                    <thead>
                        <tr>
                            <th class="product-h">Product</th>
                            <th>Price</th>
                            <th class="quan">Quantity</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($cartItems)) {
                            foreach ($cartItems as $cartItem) {
                                $quantity = $cartItem['quantity'];
                                $subtotal = $cartItem['price'] * $quantity;
                        ?>
                                <tr>
                                    <td class="product-col">
                                        <img src="<?php echo $cartItem['thumbnail'] ?>" alt="">
                                        <div class="p-title">
                                            <h5><?php echo $cartItem['name'] ?></h5>
                                            <h5><?php echo $cartItem['size'] ?></h5>
                                        </div>
                                    </td>
                                    <td class="price-col">$<?php echo $cartItem['price'] ?></td>
                                    <td class="quantity-col">
                                        <div class="pro-qty">
                                            <input type="number" min="1" name="quantity[]" value="<?php echo $quantity ?>" readonly>
                                        </div>
                                    </td>
                                    <td class="total">$<?php echo number_format($subtotal, 2, '.') ?></td>
                                    <td class="product-close" style="cursor:auto;">
                                        <a style="text-decoration: none; color: black;" href="shopping-cart.php?id=<?php echo $cartItem['id'] ?>">x</a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="4" align="center">No product to display!</td></tr>';
                        }
                        ?>
                        <tr>
                            <td colspan="4" align="center"><a class="select-more" href="categories.php">Select more products</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="cart-btn">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="coupon-input">
                            <input type="text" maxlength="5" placeholder="Enter cupone code (just 1 code)" name="coupon_code">
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1 text-left text-lg-right">
                        <input type="submit" class="site-btn update-btn" name="clear-cart" value="Clear Cart">
                        <input type="submit" class="site-btn update-btn" name="update" value="Update Cart">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <form action="check-out.php" method="post">

        <div class="shopping-method">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="shipping-info">
                            <h5>Choose a shipping</h5>
                            <div class="chose-shipping">
                                <div class="cs-item">
                                    <input type="radio" name="cs" value="Free standard shipping (2-3 days)" id="one" onclick="updateShippingFee(0)" checked>
                                    <label for="one" class="active">
                                        Free standard shipping (2-3 days)
                                        <span>Estimate for France</span>
                                    </label>
                                </div>
                                <div class="cs-item">
                                    <input type="radio" name="cs" value="Next Day delivery $10" id="two" onclick="updateShippingFee(10)">
                                    <label for="two">
                                        Next Day delivery $10
                                    </label>
                                </div>
                                <div class="cs-item last">
                                    <input type="radio" name="cs" value="In Store Pickup - Free" id="three" onclick="updateShippingFee(0)">
                                    <label for="three">
                                        In Store Pickup - Free
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="total-info">
                            <div class="total-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Total</th>
                                            <th>Discount</th>
                                            <th>Shipping</th>
                                            <!-- <th>Voucher</th> -->
                                            <th class="total-cart">Total Cart</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                            $subtotal = 0;
                                            foreach ($cartItems as $cartItem) {
                                                $quantity = $cartItem['quantity'];
                                                $subtotal += $cartItem['price'] * $quantity;
                                            }

                                            $shippingFee = 0; // Phí vận chuyển cố định

                                            $discountAmount = isset($_SESSION['discount']) ? $_SESSION['discount'] : 0;
                                            // echo "Subtotal: " . $subtotal . "<br>";
                                            // echo "Shipping Fee: " . $shippingFee . "<br>";
                                            // echo "Discount Amount: " . $discountAmount . "<br>";

                                            $total = $subtotal + $shippingFee - $discountAmount;
                                            ?>
                                            <td class="total">$<?php echo number_format($subtotal, 2, '.') ?></td>
                                            <td>$<?php echo number_format($discountAmount, 2, '.') ?></td>
                                            <td class="shipping">$<?php echo number_format($shippingFee, 2, '.') ?></td>
                                            <td class="total-cart-p">$<?php echo number_format($total, 2, '.') ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <input type="hidden" name="shipping_fee" value="<?php echo $shippingFee ?>" id="shipping-fee-input">
                                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                                    <input type="hidden" name="coupon_code" value="<?php echo isset($_SESSION['coupon_code']) ? $_SESSION['coupon_code'] : ''; ?>">
                                    <button type="submit" name="checkout" class="primary-btn chechout-btn" onclick="checkCartEmpty(event)">Proceed to checkout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Cart Page Section End -->
<!-- Footer Section Begin -->
<script>
    function checkCartEmpty(event) {
        var cartItems = <?php echo json_encode($cartItems); ?>; // Lấy thông tin giỏ hàng từ PHP và chuyển đổi thành JavaScript object

        if (cartItems.length === 0) {
            alert("Your cart is empty. Please add some products to your cart before proceeding to checkout.");
            event.preventDefault(); // Ngăn chặn quá trình chuyển đến trang thanh toán
        }

        return true; // Cho phép chuyển đến trang thanh toán
    }

    function updateShippingFee(fee) {
        document.querySelector('.shipping').innerHTML = '$' + fee.toFixed(2); // Cập nhật phí vận chuyển hiển thị
        var total = <?php echo $subtotal ?> + fee - <?php echo $discountAmount ?>;
        document.querySelector('.total-cart-p').innerHTML = '$' + total.toFixed(2); // Cập nhật tổng giá trị đơn hàng hiển thị
        document.getElementById('shipping-fee-input').value = fee.toFixed(2); // Cập nhật giá trị phí vận chuyển trong input ẩn

    }
</script>
<?php include('layout/footer.php') ?>