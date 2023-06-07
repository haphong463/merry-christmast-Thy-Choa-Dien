<?php
session_start();
require_once '../db/dbhelper.php';
require_once '../sendmail.php';
// require_once 'sendmail.php';
header('Content-type: text/html; charset=utf-8');
$email = isset($_POST['email']) ? $_POST['email'] : header('Location: signin.php');
function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        )
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    return $result;
}


$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
$sql = "SELECT * FROM cart WHERE c_id = '$email'";
$cartItems = executeResult($sql);


$orderCode = isset($_POST['orderCode']) ? $_POST['orderCode'] : NULL;
$transactionCode = isset($_POST['transactionCode']) ? $_POST['transactionCode'] : NULL;
$couponCode = isset($_POST['coupon_code']) ? $_POST['coupon_code'] : NULL;
$shipping_method = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : NULL;
$shipping_fee = isset($_POST['shipping_fee']) ? $_POST['shipping_fee'] : 0;
$payment_method = 'Momo';
$status = 0;
$payment_status = NULL;





$TOTAL = isset($_POST['amount']) ? $_POST['amount'] : 0;

$quantity = "SELECT sum(quantity) as quantity FROM cart WHERE c_id = '$email'";
$run_quantity = executeSingleResult($quantity);

$sizeMapping = array(
    'S' => 'Small',
    'M' => 'Medium',
    'L' => 'Large',
    'XL' => 'Extra Large'
);

$sql = "SELECT * FROM users WHERE username = '$username' or email = '$email'";

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


$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
$orderInfo = $orderCode;
$amount = isset($_POST['amount']) ? intval($_POST['amount'] * 23482.5) : 0;
$orderId = time() . "";
$redirectUrl = "http://localhost/clothing-store-main/thanks.php";
$ipnUrl = "http://localhost/clothing-store-main/thanks.php";
$extraData = "";



$requestId = time() . "";
$requestType = "captureWallet";
// $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
//before sign HMAC SHA256 signature
$rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
$signature = hash_hmac("sha256", $rawHash, $secretKey);
$data = array(
    'partnerCode' => $partnerCode,
    'partnerName' => "Test",
    "storeId" => "MomoTestStore",
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature
);
$result = execPostRequest($endpoint, json_encode($data));
$jsonResult = json_decode($result, true);  // decode json

//Just a example, please check more in there




header('Location: ' . $jsonResult['payUrl']);
