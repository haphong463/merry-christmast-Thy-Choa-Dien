    <?php include('layout/header.php') ?>
    <?php
    require_once('db/dbhelper.php');
    require_once('sendmail.php');
    require_once('vnpay_php/config.php');

    if (!isset($_SESSION['user'])) {
        echo '<script>window.location.href = "signin.php";</script>';
        exit();
    }

    $sql = "SELECT * FROM cart WHERE c_id = '$email'";
    $cartItems = executeResult($sql);

    if (empty($cartItems)) {
        echo '<script>alert("Your cart is empty. Please add some products to your cart before proceeding to checkout.");</script>';
        echo '<script>window.location.href = "shopping-cart.php";</script>';
        exit();
    }

    if (isset($_POST['checkout'])) {
        $_SESSION['shipping_method'] = isset($_POST['cs']) ? $_POST['cs'] : '';
        $_SESSION['total'] = isset($_POST['total']) ? $_POST['total'] : 0;
        $_SESSION['shipping_fee'] = isset($_POST['shipping_fee']) ? $_POST['shipping_fee'] : 0;
        $_SESSION['coupon_code'] = isset($_POST['coupon_code']) ? $_POST['coupon_code'] : 0;
        echo '<script>window.location.href = "check-out.php";</script>';
    }

    $TOTAL = $_SESSION['total'] + $_SESSION['shipping_fee'];

    $sql = "SELECT * FROM users WHERE username = '$username' or email = '$email'";

    $users = executeSingleResult($sql);
    $fullname = $users['full_name'];
    $address = $users['address'];
    $contact = $users['contact'];
    $dob = $users['date_of_birth'];


    function generateOrderCode()
    {
        $digits = '0123456789';
        $orderCode = 'VN';
        for ($i = 0; $i < 6; $i++) {
            $orderCode .= $digits[rand(0, 9)];
        }
        return $orderCode;
    }

    function generateTransactionID()
    {
        $transactionID = '#';
        for ($i = 0; $i < 7; $i++) {
            $transactionID .= rand(0, 9);
        }
        return $transactionID;
    }

    function resetTotalValue()
    {
        unset($_SESSION['total']);
        unset($_SESSION['shipping_fee']);
        unset($_SESSION['coupon_code']);
        $TOTAL = 0;
    }

    $orderCode = generateOrderCode();
    $transactionCode = generateTransactionID();

    $sql_cart = "SELECT * FROM cart WHERE c_id = '$email'";
    $run_sql_cart = executeResult($sql_cart);

    $quantity = "SELECT sum(quantity) as quantity FROM cart WHERE c_id = '$email'";
    $run_quantity = executeSingleResult($quantity);

    $sizeMapping = array(
        'S' => 'Small',
        'M' => 'Medium',
        'L' => 'Large',
        'XL' => 'Extra Large'
    );



    if (isset($_POST['payment'])) {

        $orderDetails = array();

        foreach ($run_sql_cart as $item) {
            $productId = $item['pid'];
            $quantity = $item['quantity'];
            $size = $item['size'];

            $orderDetails[] = array(
                'pid' => $productId,
                'quantity' => $quantity,
                'size' => $size
            );
        }

        foreach ($orderDetails as $detail) {
            $productId = $detail['pid'];
            $quantity = $detail['quantity'];
            $size = $detail['size'];

            $sql_order_details = "INSERT INTO order_details (c_id, order_id, pid, quantity, size) VALUES ('$email', '$orderCode', $productId, $quantity, '$size')";
            execute($sql_order_details);
        }


        $quantity = $run_quantity['quantity'];
        $cartQuantity = 0;

        $sizes = array();
        $products = array();

        if ($_POST['payment'] == "Paypal") {
            $payment_method = $_POST['payment'];
            $payment_status = 1;
            $status = '0';
        } elseif ($_POST['payment'] == "Get package") {
            $payment_method = $_POST['payment'];
            $payment_status = 0;
            $status = '0';
        } elseif ($_POST['payment'] == "VNPAY") {
            $payment_method = $_POST['payment'];
            $payment_status = 0;
            $status = '0';
            $vnp_TxnRef = $transactionCode;; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = $orderCode;
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $TOTAL * 2348250;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            //Add Params of 2.0.1 Version
            $vnp_ExpireDate = $expire;

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => $vnp_ExpireDate,
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            // }

            //var_dump($inputData);
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            $returnData = array(
                'code' => '00', 'message' => 'success', 'data' => $vnp_Url
            );
            if (isset($_POST['redirect'])) {
                echo '<script>window.location.href = "' . $vnp_Url .  '";</script>';
                die();
            } else {
                echo json_encode($returnData);
            }
        }





        foreach ($run_sql_cart as $cart) {
            $size = $cart['size'];
            $pid = $cart['pid'];

            $cartQuery = "SELECT quantity FROM cart WHERE c_id = '$email' AND pid = '$pid' AND size = '$size'";
            $cartResult = executeSingleResult($cartQuery);

            if ($cartResult) {
                $cartQuantity = $cartResult['quantity'];

                if (isset($sizeMapping[$size])) {
                    $displaySize = $sizeMapping[$size];
                } else {
                    $displaySize = $size;
                }

                switch ($displaySize) {
                    case 'Medium':
                        $displaySize = 'M';
                        break;
                    case 'Large':
                        $displaySize = 'L';
                        break;
                    case 'Extra Large':
                        $displaySize = 'XL';
                        break;
                    case 'Small':
                        $displaySize = 'S';
                        break;
                }

                $quantityQuery = "UPDATE product_variant SET quantity = quantity - $cartQuantity WHERE pid = $pid AND size = '$displaySize'";
                execute($quantityQuery);

                $product_query = "SELECT name FROM product WHERE pid = '$pid'";
                $product_result = executeSingleResult($product_query);
                $product_name = $product_result['name'];

                $products[] = $product_name;
                $sizes[] = $size;
            }
        }

        $shipping_method = isset($_SESSION['shipping_method']) ? $_SESSION['shipping_method'] : '';
        $shipping_fee = isset($_SESSION['shipping_fee']) ? $_SESSION['shipping_fee'] : 0;

        if (isset($_SESSION['coupon_code'])) {
            $couponCode = $_SESSION['coupon_code'];
            $sqlDiscount = "UPDATE discount SET quantity = quantity - 1 WHERE coupon_code = '$couponCode'";
            execute($sqlDiscount);
        }

        $sql = "INSERT INTO orders (order_id, date, c_id, shipping_method, contact, shipping_fee, address, total, voucher) 
                    VALUES ('$orderCode', NOW(), '$email', '$shipping_method', '$contact',  $shipping_fee, '$address', $TOTAL, '$couponCode')";
        execute($sql);

        $sql_transaction = "INSERT INTO transaction (transaction_id, order_id, payment_method, transaction_date, amount, status, payment_status, created_at, updated_at)
                                                VALUES ('$transactionCode', '$orderCode', '$payment_method', NOW(), $TOTAL, '$status', '$payment_status', NOW(), NOW())";
        execute($sql_transaction);



        $title = 'Notice: ORDER #' . $orderCode . '';
        $content = '<html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                        background-color: #fff;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                    }

                    a.tab-button {
                        text-decoration: none;
                        font-size: 14px;
                        font-weight: 600;
                        position: relative;
                        letter-spacing: .02em;
                        display: block;
                        padding: 10px 25px;
                        outline: none;
                        background: #eee;
                        color: #333;
                        border: none;
                    }
                    a.tab-button.active,
                    a.tab-button:hover {
                        transition: .5s;
                        opacity: 1;
                        text-decoration: none;
                        background-color: #fff;
                        color: #000;
                    }

                    h1 {
                        color: #333;
                        font-size: 24px;
                    }
                    p {
                        font-size: 16px;
                        line-height: 24px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Order Confirmation</h1>
                    <p>Dear ' . $fullname . ',</p>
                    <p>Thank you for placing an order with our store. Your order has been received and is being processed.</p>

                    <p>We are excited to let you know that our team is working diligently to prepare your items for shipment. Once your order has been shipped, we will send you a notification with the tracking details.</p>

                    <p>Please take a moment to review your order details below:</p>

                    <!-- Insert order details here -->
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Information</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($cartItems as $order_details) {
            $pid = $order_details['pid'];
            $size = $order_details['size'];
            $quantity = $order_details['quantity'];

            $sql = "SELECT thumbnail FROM product_thumbnail WHERE pid = $pid";
            $image = executeSingleResult($sql);

            $sql2 = "SELECT * FROM product WHERE pid = $pid";
            $product = executeSingleResult($sql2);
            $content .= '
                        <tr>
                            <td><img src="' . $image['thumbnail'] . '"></td>                          
                            <td>' . $product['name'] . '</td>
                            <td>' . $quantity . '</td>
                            <td>' . number_format($product['price'], 2, '.') . '</td>
                        </tr>';
        }

        $content .= '   
                        </tbody>
                    </table>

                    <p>
                        Total amount: $' . number_format($TOTAL, 2, '.') . '
                    </p>

                    <p>If you have any questions or need further assistance, please don\'t hesitate to contact our customer support team at support@chicandcool.com.</p>

                    <p>
                        <a class="tab-button" href="localhost/clothing-store/order-details.php?order_id=' . $orderCode . '">View Order</a>
                        <a class="tab-button" href="localhost/clothing-store">Visit Website</a>
                    </p>

                    <p>Once again, thank you for choosing our store. We truly appreciate your business!</p>

                    <p>Sincerely,</p>
                    <p>Chic & Cool</p>
                </div>
            </body>
        </html>';




        $mailer = new Mailer();
        $mailer->dathangmail($title, $content, $email);









        $sqlDeleteCart = "DELETE FROM cart WHERE c_id = '$email'";
        execute($sqlDeleteCart);

        resetTotalValue();


        echo '<script>window.location.href = "thanks.php";</script>';
        exit();
    }



    ?>


    <!-- Page Add Section Begin -->
    <section class="page-add">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="page-breadcrumb">
                        <h2>Checkout<span>.</span></h2>
                    </div>
                </div>
                <?php
                include('layout/discount.php');
                ?>
            </div>
        </div>
    </section>
    <!-- Page Add Section End -->

    <!-- Cart Total Page Begin -->
    <section class="cart-total-page spad">
        <div class="container">
            <form action="" class="checkout-form">
                <div class="row">
                    <div class="col-lg-12">
                        <h3>Your Information</h3>
                    </div>
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-2">
                                <p class="in-name">Name*</p>
                            </div>
                            <div class="col-lg-10">
                                <input type="text" value="<?php echo $fullname ?>" readonly>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <p class="in-name">Email*</p>
                            </div>
                            <div class="col-lg-10">
                                <input type="text" value="<?php echo $email ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <p class="in-name">Street Address*</p>
                            </div>
                            <div class="col-lg-10">
                                <input type="text" value="<?php echo $address ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <p class="in-name">Date of birth*</p>
                            </div>
                            <div class="col-lg-10">
                                <input type="text" value="<?php echo $dob ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <p class="in-name">Phone*</p>
                            </div>
                            <div class="col-lg-10">
                                <input type="text" value="<?php echo $contact ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <div class="diff-addr">
                                    <input type="radio" id="one">
                                    <label for="one">Ship to different address</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="order-table">
                            <div class="cart-item">
                                <span>Product</span>
                                <?php
                                if ($run_sql_cart != null) {
                                    foreach ($run_sql_cart as $item) {
                                        $pid = $item['pid'];
                                        $sql_product = "SELECT * FROM product WHERE pid = '$pid'";
                                        $product = executeResult($sql_product);
                                        if ($product != null) {
                                            foreach ($product as $p) {

                                                echo '
                                                    <p class="product-name">' . $p['name'] . '</p>   
                                                    <br>                               
                                                    ';
                                            }
                                        }
                                    }
                                } else {
                                    echo '<p class="product-name">No items</p>';
                                }
                                ?>
                            </div>
                            <div class="cart-item">
                                <span>Price</span>
                                <p>$<?php echo number_format($_SESSION['total'], 2, '.') ?></p>
                            </div>
                            <div class="cart-item">
                                <span>Quantity</span>
                                <p>
                                    <?php
                                    if ($run_quantity['quantity'] == NULL) {
                                        echo 0;
                                    } else {
                                        echo $run_quantity['quantity'];
                                    } ?>
                                </p>
                            </div>
                            <div class="cart-item">
                                <span>Shipping</span>
                                <p>$<?php echo $_SESSION['shipping_fee'] ?></p>
                            </div>

                            <div class="cart-total">
                                <span>Total</span>
                                <p>$<?php echo number_format($TOTAL, 2, '.')  ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-lg-12">

                    <div class="payment-method">
                        <h3>Payment</h3>
                        <div class="row">
                            <div class="col-lg-6">
                                <form id="theForm" action="check-out.php" method="post">

                                    <div id="payment"></div>
                                    <!-- Mã PayPal -->
                                    <script src="https://www.paypal.com/sdk/js?client-id=ATGXcrNc5l8akd8iyRwk-OI4GXTyXAQy_nybdU9fGSfHpFA3crp3AUjbFIHEKYuiGyTkLpczjCgFS2GH"></script>
                                    <div id="paypal-button-container"></div>
                                    <script>
                                        paypal.Buttons({
                                            createOrder: function(data, actions) {
                                                // This function sets up the details of the transaction, including the amount and line item details.
                                                return actions.order.create({
                                                    purchase_units: [{
                                                        amount: {
                                                            value: "<?php echo ($TOTAL); ?>"
                                                        }
                                                    }]
                                                });
                                            },
                                            onApprove: function(data, actions) {
                                                // This function captures the funds from the transaction.
                                                return actions.order.capture().then(function(details) {
                                                    // This function shows a transaction success message to your buyer.
                                                    document.getElementById('payment').innerHTML = '<input name="payment" value="Paypal" hidden>'
                                                    document.getElementById('theForm').submit();
                                                });
                                            }
                                        }).render('#paypal-button-container');
                                        //This function displays Smart Payment Buttons on your web page.
                                    </script>
                                </form>
                                <form id="momoForm" class="" method="POST" enctype="application/x-www-form-urlencoded" action="process/momo.php">
                                    <input type="hidden" name="email" value="<?php echo $email ?>">
                                    <input type="hidden" name="shipping_method" value="<?php echo $_SESSION['shipping_method'] ?>">
                                    <input type="hidden" name="shipping_fee" value="<?php echo $_SESSION['shipping_fee'] ?>">
                                    <input type="hidden" name="coupon_code" value="<?php echo $couponCode ?>">
                                    <input type="hidden" name="orderCode" value="<?php echo $orderCode ?>">
                                    <input type="hidden" name="transactionCode" value="<?php echo $transactionCode ?>">
                                    <input type="hidden" name="amount" value="<?php echo $TOTAL ?>">
                                    <input type="image" src="assets/img/MoMo_Logo.png" alt="MoMo" id="momoButton" />
                                </form>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <form action="check-out.php" method="post">
                                        <input type="hidden" name="coupon" value="<?php echo $_SESSION['coupon_code'] ?>">
                                        <input type="hidden" name="shipping_fee" value="<?php echo $_SESSION['shipping_fee'] ?>">
                                        <ul>
                                            <li>
                                                <label for="two">VNPAY</label>
                                                <input type="radio" name="payment" value="VNPAY" id="two">
                                            </li>
                                            <li>
                                                <label for="three">Pay when you get the package</label>
                                                <input type="radio" name="payment" value="Get package" id="three">
                                            </li>
                                            <li>
                                                <button type="submit" name="redirect">Place Order</button>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                                <div class="col-lg-12 text-center">
                                </div>
                            </div> -->
                    </div>
                </div>
            </div>





        </div>
    </section>
    <!-- Cart Total Page End -->
    <script>
        // Gắn kết sự kiện "click" vào hình ảnh MoMo
        document.getElementById("momoButton").addEventListener("click", function() {
            // Gọi phương thức submit() của form
            document.getElementById("momoForm").submit();
        });
    </script>
    <?php include('layout/footer.php') ?>