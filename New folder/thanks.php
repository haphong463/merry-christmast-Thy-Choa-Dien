<?php
include('layout/header.php');
?>
<?php
if (!isset($_SESSION['user'])) {
    echo '<script> window.location.href= "signin.php"</script>';
}

require_once 'sendmail.php';


if (isset($_GET['partnerCode'])) {
    $partnerCode = $_GET['partnerCode'];
    $orderID = $_GET['orderId'];
    $requestID = $_GET['requestId'];
    $amount = $_GET['amount'];
    $orderInfo = $_GET['orderInfo'];
    $orderType = $_GET['orderType'];
    $transId = $_GET['transId'];
    $resultCode = $_GET['resultCode'];
    $message = $_GET['message'];
    $payType = $_GET['payType'];
    $responseTime = $_GET['responseTime'];
    $extraDATA = $_GET['extraData'];
    if ($resultCode == 0) {
        $sql_update = "UPDATE transaction SET payment_status = 1 WHERE order_id = '$orderInfo'";
        execute($sql_update);
    } else {
        $sql_delete_transaction = "DELETE FROM transaction WHERE order_id = '$orderInfo'";
        execute($sql_delete_transaction);

        $sql_delete_orders = "DELETE FROM orders WHERE order_id = '$orderInfo'";
        execute($sql_delete_orders);

        $sql_delete_order_details = "DELETE FROM order_details WHERE order_id = '$orderInfo'";
        execute($sql_delete_order_details);
    }
    echo '<script>window.location.href = "thanks.php"</script>';
} elseif (isset($_GET['vnp_Amount'])) {
    $vnp_Amount = $_GET['vnp_Amount'];
    $TOTAL = $vnp_Amount / 2348250;
    $vnp_BankCode = $_GET['vnp_BankCode'];
    $vnp_BankTranNo = $_GET['vnp_BankTranNo'];
    $vnp_cardType = $_GET['vnp_CardType'];
    $vnp_OrderInfo = $_GET['vnp_OrderInfo'];
    $vnp_PayDate = $_GET['vnp_PayDate'];
    $vnp_ResponseCode = $_GET['vnp_ResponseCode'];
    $vnp_TmnCode = $_GET['vnp_TmnCode'];
    $vnp_TransactionNo = $_GET['vnp_TransactionNo'];
    $vnp_TxnRef = $_GET['vnp_TxnRef'];
    $payment_method = "VNPAY";
    $payment_status = NULL;

    $sql = "SELECT * FROM cart WHERE c_id = '$email'";
    $cartItems = executeResult($sql);

    $insert_vnpay = "INSERT INTO `vnpay` (`id_vnpay`, `vnp_amount`, `vnp_bankCode`, `vnp_banktranno`, `vnp_cardtype`, `vnp_orderinfo`, `vnp_paydate`, 
    `vnp_tmncode`, `vnp_transactionno`, `order_id`) VALUES (NULL, '$vnp_Amount', '$vnp_BankCode', '$vnp_BankTranNo', 
    '$vnp_cardType', '$vnp_OrderInfo', '$vnp_PayDate', '$vnp_TmnCode', '$vnp_TransactionNo', '$vnp_OrderInfo');";
    execute($insert_vnpay);


    $quantity = "SELECT sum(quantity) as quantity FROM cart WHERE c_id = '$email'";
    $run_quantity = executeSingleResult($quantity);

    $sizeMapping = array(
        'S' => 'Small',
        'M' => 'Medium',
        'L' => 'Large',
        'XL' => 'Extra Large'
    );

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $users = executeSingleResult($sql);
    $fullname = $users['full_name'];
    $address = $users['address'];
    $contact = $users['contact'];
    $dob = $users['date_of_birth'];


    $sql_cart = "SELECT * FROM cart WHERE c_id = '$email'";
    $run_sql_cart = executeResult($sql_cart);

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

    $quantity = $run_quantity['quantity'];
    $cartQuantity = 0;

    $sizes = array();
    $products = array();

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

    $productString = implode(', ', $products);
    $sizeString = implode(', ', $sizes);

    $shipping_method = isset($_SESSION['shipping_method']) ? $_SESSION['shipping_method'] : '';
    $shipping_fee = isset($_SESSION['shipping_fee']) ? $_SESSION['shipping_fee'] : 0;

    if (isset($_SESSION['coupon_code'])) {
        $couponCode = $_SESSION['coupon_code'];
        $sqlDiscount = "UPDATE discount SET quantity = quantity - 1 WHERE coupon_code = '$couponCode'";
        execute($sqlDiscount);
    }

    $sql = "INSERT INTO orders (order_id, date, c_id, shipping_method, contact, shipping_fee, address, total, voucher) 
    VALUES ('$vnp_OrderInfo', NOW(), '$email', '$shipping_method', '$contact',  $shipping_fee, '$address', $TOTAL, '$couponCode')";
    execute($sql);


    $sql_transaction = "INSERT INTO transaction (transaction_id, order_id, payment_method, transaction_date, amount, status, payment_status, created_at, updated_at)
                                        VALUES ('$vnp_TxnRef', '$vnp_OrderInfo', '$payment_method', NOW(), $TOTAL, '$status', '$payment_status', NOW(), NOW())";
    execute($sql_transaction);



    $title = 'Notice: ORDER #' . $vnp_OrderInfo . '';
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
                <a class="tab-button" href="localhost/clothing-store/order-details.php?order_id=' . $vnp_OrderInfo . '">View Order</a>
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

    if ($vnp_ResponseCode == 0) {
        $sql_update = "UPDATE transaction SET payment_status = 1 WHERE order_id = '$vnp_OrderInfo'";
        execute($sql_update);
    } else {
        $sql_delete_transaction = "DELETE FROM transaction WHERE order_id = '$vnp_OrderInfo'";
        execute($sql_delete_transaction);

        $sql_delete_orders = "DELETE FROM orders WHERE order_id = '$vnp_OrderInfo'";
        execute($sql_delete_orders);

        $sql_delete_order_details = "DELETE FROM order_details WHERE order_id = '$vnp_OrderInfo'";
        execute($sql_delete_order_details);
    }

    echo '<script>window.location.href = "thanks.php"</script>';
}
?>
<section class="page-add thanks">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Thank you for shopping with us!</h3>
                <p>We sincerely appreciate and value your trust in our store. Your order has been received and is being processed. We will deliver your items as soon as possible.</p>
                <p>If you have any questions or requests, please feel free to contact us using the phone number or email provided below.</p>
                <p>Thank you once again for choosing our store!</p>
                <div class="contact-details">
                    <p><i class="fa fa-phone"></i> Phone: 123-456-789</p>
                    <p><i class="fa fa-envelope"></i> Email: <a href="mailto: chicandcool@gmail.com">chicandcool@gmail.com</a></p>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include('layout/footer.php'); ?>