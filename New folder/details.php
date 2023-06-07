<?php include('layout/header.php');
if(!isset($_SESSION['user'])){
    echo '<script>window.location.href = "signin.php"; </script>';
}
?>

<?php
require_once 'db/dbhelper.php';
$order_query = "SELECT * FROM orders WHERE c_id = '$email' ORDER BY date desc";
$order_result = executeResult($order_query);

?>

<!-- Header Info End -->
<!-- Header End -->

<!-- Page Add Section Begin -->
<section class="page-add cart-page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2>My account<span>.</span></h2>
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
<div class="cart-page" style="margin-bottom: 50vh;">
    <div class="container">
        <h2>Order history</h2>
        <div class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Payment Status</th>
                        <th>Complete Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($order_result as $order) {
                        $order_id = $order['order_id'];
                        $transaction_qeury = "SELECT * FROM transaction WHERE order_id = '$order_id' ";
                        $transaction_result = executeSingleResult($transaction_qeury);


                        $status = '';
                        $payment_status = '';

                        switch ($transaction_result['payment_status']) {
                            case 0:
                                $payment_status = "COD";
                                break;
                            case 1:
                                $payment_status = "Paid";
                                break;
                        }

                        switch ($transaction_result['status']) {
                            case 0:
                                $status = "Order Placed";
                                break;
                            case 1:
                                $status = "Processing";
                                break;
                            case 2:
                                $status = "Shipping";
                                break;
                            case 3:
                                $status = "Delivered";
                                break;
                        }

                        echo '
                            <tr>
                                <td><a style="border-radius: 0" class="btn btn--secondary btn--small" href="order-details.php?order_id=' . $order['order_id'] . '">' . $order['order_id'] . '</a></td>
                                <td>' . $order['date'] . '</td>
                                <td>' . $payment_status . ' </td>
                                <td>' . $status . '</td>
                                <td><b>$' . number_format($order['total'], 2, '.') . '</b></td>
                            <tr>
                            ';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- Cart Page Section End -->

<!-- Footer Section Begin -->
<?php include('layout/footer.php') ?>