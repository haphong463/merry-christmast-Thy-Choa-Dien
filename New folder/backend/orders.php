<?php
require_once '../db/dbhelper.php';
$sql_transaction = "SELECT * FROM transaction AS t INNER JOIN orders AS o ON t.order_id = o.order_id ORDER BY t.transaction_date desc";
$run_sql_transaction = executeResult($sql_transaction);
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
                                    <h3>Order List
                                        <small>La Mode Parisienne</small>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ol class="breadcrumb pull-right">
                                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Digital</li>
                                    <li class="breadcrumb-item active">Orders</li>
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
                                    <h5>Digital Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div id="" class="product-physical">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Transaction ID</th>
                                                        <th scope="col">Order ID</th>
                                                        <th scope="col">Customer</th>
                                                        <th scope="col">Payment Method</th>
                                                        <th scope="col">Payment Status</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($run_sql_transaction != NULL) {
                                                        foreach ($run_sql_transaction as $transaction) {
                                                            $payment_status = '';
                                                            $status = '';


                                                            $status = '';
                                                            $payment_status = '';

                                                            switch ($transaction['payment_status']) {
                                                                case 0:
                                                                    $payment_status = "COD";
                                                                    break;
                                                                case 1:
                                                                    $payment_status = "Paid";
                                                                    break;
                                                            }

                                                            switch ($transaction['status']) {
                                                                case 0:
                                                                    $status = "Order Placed";
                                                                    break;
                                                                case 1:
                                                                    $status = "Shipping";
                                                                    break;
                                                                case 2:
                                                                    $status = "Delivered";
                                                                    break;
                                                            }
                                                            echo '
                                                            <tr>
                                                            <td><b>' . $transaction['transaction_id'] . '<b></td>
                                                            <td><b>' . $transaction['order_id'] . '</b></td>
                                                            <td>' . $transaction['c_id'] . '</td>
                                                            <td>' . $transaction['payment_method'] . '</td>
                                                            <td>' . $payment_status . '</td>
                                                            <td>' . $status . '</td>
                                                            <td><a href="view-order.php?order_id=' . $transaction['order_id'] . '">View Order</a></td>
                                                            </tr>
                                                            ';
                                                        }
                                                    } else {
                                                        echo '
                                                        <tr>
                                                            <td style="text-align:Center" colspan="6">No transaction to display!</td>
                                                        </tr>
                                                        ';
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
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