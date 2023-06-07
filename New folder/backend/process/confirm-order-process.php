<?php
require_once '../../db/dbhelper.php';
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $sql_update_status_transaction = "UPDATE transaction SET status = 1 WHERE order_id = '$order_id'";
    execute($sql_update_status_transaction);

    header('Location: ../orders.php');
} else {
    header('Location: ../index.php');
}
