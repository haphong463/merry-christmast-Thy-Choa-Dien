<?php
include('layout/header.php');
require_once 'db/dbhelper.php';
if (!isset($_SESSION['user'])) {
    echo '<script>window.location.href = "signin.php";</script>';
    exit();
} else {
    if ($_GET['order_id']) {
        $order_id = $_GET['order_id'];
        $order_query = "SELECT * FROM order_details WHERE order_id = '$order_id' and c_id = '$email'";
        $order_result = executeResult($order_query);

        if (!empty($order_result)) {
            foreach ($order_result as $order) {
                $product_id =  $order['pid'];
            }
            // Lấy thông tin sản phẩm từ bảng "product" dựa trên các tên sản phẩm đã tách được

            $product_query = "SELECT pid, cat_id, name, color, price FROM product WHERE pid = $product_id";
            $product_result = executeResult($product_query);
        }

        // Sử dụng thông tin sản phẩm trong mảng $products để hiển thị trong trang Order Details

        $sql4 = "SELECT * FROM orders WHERE order_id = '$order_id' and c_id = '$email'";
        $total = executeSingleResult($sql4);
        if (!isset($total)) {
            echo '<script>window.location.href = "details.php";</script>';
            exit();
        }

        $order_date = $total['date'];

        $transaction_query = "SELECT * FROM transaction WHERE order_id = '$order_id'";
        $transaction_result = executeSingleResult($transaction_query);
    }
}



?>

<section class="page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2><b>ORDER #<?php echo $order_id ?></b><span>.</span></h2>
                </div>
            </div>
            <?php
            include('layout/discount.php');
            ?>
        </div>
    </div>
</section>
<div class="page-body">

    <!-- Container-fluid starts-->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <b>ORDER #<?php echo $order_id ?></b>
                        <b style="margin-left:30px;"><?php echo $order_date ?></b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Information</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $subtotal = 0; // Khởi tạo biến subtotal

                                            foreach ($order_result as $order_details) {
                                                $pid = $order_details['pid'];
                                                $size = $order_details['size'];
                                                $quantity = $order_details['quantity'];

                                                $sql = "SELECT thumbnail FROM product_thumbnail WHERE pid = $pid";
                                                $image = executeSingleResult($sql);

                                                $sql2 = "SELECT * FROM product WHERE pid = $pid";
                                                $product = executeSingleResult($sql2);
                                                $cat_id = $product['cat_id'];

                                                $sql3 = "SELECT * FROM category WHERE cat_id = $cat_id";
                                                $category = executeSingleResult($sql3);

                                                $total_amount_item = $quantity * $product['price'];

                                                $subtotal += $total_amount_item;

                                                echo '
                                                            
                                                            <tr>
                                                            
                                                            <td>
                                                                <div class="product-info">
                                                                    <div class="product-image">
                                                                        <img src="' . $image['thumbnail'] . '" width="250px" height="250px" alt="Product Image">
                                                                    </div>
                                                                    <div class="product-details">
                                                                        <h6 class="product-name">' . $product['name'] . '</h6>
                                                                        <p class="product-info">Color: ' . $product['color'] . '</p>
                                                                        <p class="product-info">Size: ' . $size . '</p>
                                                                        <p class="product-info">Gender: ' . $category['cat_name'] . '</p>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td>$' . number_format($product['price'], 2, '.') . '</td>
                                                            <td>' . $quantity . '</td>
                                                            <td>$' . number_format($total_amount_item, 2, '.') . '</td>
                                                        </tr>
                                                            
                                                            ';
                                            }
                                            ?>
                                        </tbody>

                                        <tfoot>
                                            <?php

                                            $sql5 = "SELECT * FROM discount WHERE coupon_code = '{$total['voucher']}'";
                                            $coupon = executeSingleResult($sql5);

                                            $discount_amount = isset($coupon['discount']) ? $coupon['discount'] : 0;
                                            $code = isset($coupon['coupon_code']) ? "(Code: " . $coupon['coupon_code'] . ")" : '';

                                            echo '
                                                        
                                                        <tr>
                                                            <td colspan="3"><b>Sub Total:</b></td>
                                                            <td>$' . number_format($subtotal, 2, '.') . '</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><b>Discount ' . $code . ':</b></td>
                                                            <td>' . number_format($discount_amount, 2, '.') . '%</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><b>Shipping Fee:</b></td>
                                                            <td>$' . number_format($total['shipping_fee'], 2, '.') . '</td>
                                                        </tr>
                                                
                                                        <tr>
                                                            <td colspan="3"><b>Total:</b></td>
                                                            <td><b>$' . number_format($total['total'], 2, '.') . '</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"></td>';
                                            if ($transaction_result['status'] == 0) {

                                                echo '
                                                            <td>
                                                            <!-- Button to open the pop-up -->
                                                            <a class="order_back" href="#" onclick="openPopup()">CANCEL ORDER</a>
                                                            
                                                            <!-- The pop-up -->
                                                            <div class="popup" id="popup">
                                                              <div class="popup-content">
                                                                <h2>Do you want to cancel this order?</h2>
                                                                <div class="popup-buttons">
                                                                  <a href="cancel-order-process.php?order_id=' . $order_id . '" class="confirm-btn">Yes</a>
                                                                  <button onclick="closePopup()" class="cancel-btn">No</button>
                                                                </div>
                                                              </div>
                                                            </div>                                                            
                                                            </td>
                                                        </tr>
                                                        
                                                        ';
                                            }

                                            ?>

                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Open the pop-up
    function openPopup() {
        document.getElementById('popup').classList.add('show');
    }

    // Close the pop-up
    function closePopup() {
        document.getElementById('popup').classList.remove('show');
    }
    window.addEventListener('DOMContentLoaded', function() {
        var popupLink = document.querySelector('.order_back');
        popupLink.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default behavior of the link
        });
    });
</script>
<?php include('layout/footer.php'); ?>