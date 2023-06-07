<?php
require_once '../db/dbhelper.php';
if ($_GET['order_id']) {
    $order_id = $_GET['order_id'];
    $order_query = "SELECT * FROM order_details WHERE order_id = '$order_id'";
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

    $sql4 = "SELECT * FROM orders WHERE order_id = '$order_id'";
    $total = executeSingleResult($sql4);

    $order_date = $total['date'];

    $transaction_query = "SELECT * FROM transaction WHERE order_id = '$order_id'";
    $transaction_result = executeSingleResult($transaction_query);
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
                                    <h3>Order Details
                                        <small>La Mode Parisienne</small>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ol class="breadcrumb pull-right">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Orders</li>
                                    <li class="breadcrumb-item active">Order Details</li>
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
                                    <b>ORDER #<?php echo $order_id ?></b>
                                    <b style="margin-left:30px;"><?php echo $order_date ?></b>
                                    <!-- <button onclick="window.print()">Print</button> Thêm dòng này để hiển thị ngày -->
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 mb-3 mb-lg-0">
                                            <h4>Order Details</h4>
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
                                                                        <img src="../' . $image['thumbnail'] . '" alt="Product Image">
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
                                                        ';
                                                        ?>

                                                    </tfoot>
                                                </table>
                                            </div>
                                            <?php
                                            if ($transaction_result['status'] == 0) {
                                                echo '
                                                <a href="process/confirm-order-process.php?order_id=' . $order_id . '" 
                                                style="color:White" class="btn btn-primary">CONFIRM ORDER</a>
                                                ';
                                            } else {
                                                echo '';
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        $sql6 = "SELECT * FROM users WHERE email = '{$total['c_id']}'";
                                        $users = executeSingleResult($sql6);
                                        ?>
                                        <div class="col-lg-4">
                                            <h4>Customer</h4>
                                            <div class="user-info">
                                                <h5><?php echo $users['full_name'] ?></h5>
                                                <h5>
                                                    Contact
                                                    <p>
                                                        <?php echo $users['email'] ?>
                                                    </p>
                                                    <p>
                                                        <?php echo $users['contact'] ?>
                                                    </p>

                                                </h5>
                                                <h5>
                                                    Shipping address
                                                    <p>
                                                        <?php echo $total['address'] ?>

                                                    </p>
                                                </h5>
                                            </div>
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