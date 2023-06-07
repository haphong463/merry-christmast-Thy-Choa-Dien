<?php
require_once 'db/dbhelper.php';
session_start();
if (isset($_SESSION['user'])) {
    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];

        $order_details_query = "SELECT * FROM order_details WHERE order_id = '$order_id'";
        $order_details_result = executeResult($order_details_query);

        if (!empty($order_details_result)) {
            foreach ($order_details_result as $order_details) {
                $product_id = $order_details['pid'];
                $size = $order_details['size'];
                $quantity = $order_details['quantity'];
                switch ($size) {
                    case 'Small':
                        $short_size = 'S';
                        break;
                    case 'Medium':
                        $short_size = 'M';
                        break;
                    case 'Large':
                        $short_size = 'L';
                        break;
                    case 'Extra Large':
                        $short_size = 'XL';
                        break;
                        // Thêm các trường hợp cho các size khác (nếu cần)
                    default:
                        $short_size = $size; // Mặc định giữ nguyên giá trị size nếu không tìm thấy trường hợp phù hợp
                        break;
                }
                $update_product_variant_query = "UPDATE product_variant SET quantity = quantity + $quantity WHERE pid = $product_id AND size = '$short_size'";
                echo $update_product_variant_query;
                execute($update_product_variant_query);
            }
        }

        $delete_order_details_query = "DELETE FROM order_details WHERE order_id = '$order_id'";
        execute($delete_order_details_query);

        $delete_order_query = "DELETE FROM orders WHERE order_id = '$order_id'";
        execute($delete_order_query);

        $delete_transaction_query = "DELETE FROM transaction WHERE order_id = '$order_id";
        execute($delete_transaction_query);

        header("Location: details.php");
        exit();
    }
} else {
    header('Location: signin.php');
    exit();
}
