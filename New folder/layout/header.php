<?php
session_start();

require_once('db/dbhelper.php');
$sql = "SELECT * FROM product_category";
$product_categories = executeResult($sql);

$sql2 = "SELECT * FROM category";
$product = executeResult($sql2);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Yoga Studio Template">
    <meta name="keywords" content="Yoga, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parisienne | Store</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/assets/css/font-awesome.min.css">

    <style>
        .logo-section .logo-item {

            height: auto !important;
            opacity: 1 !important;

        }

        .logo-section h2 {
            text-align: Center;
            color: #1e1e1e;
            font-size: 60px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        ::selection {
            background: #2a2a2a;
            color: #fff;
        }

        .error {
            color: red;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        button.tab-button {
            cursor: pointer;
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

        button.tab-button.active,
        button.tab-button:hover {
            transition: .5s;
            opacity: 1;
            text-decoration: none;
            background-color: #fff;
            color: #000;
        }

        .product-tabs {
            display: flex;
            justify-content: center;
        }

        .hero-items .owl-nav button[type=button].owl-next {
            left: auto;
            right: 60px;
            display: inline-block;
        }

        button.add-to-cart {
            height: 56px;
            width: 173px;
            border: 2px solid #EEF1F2;
            border-radius: 50px;
            cursor: pointer;
            color: white;
            background-color: black;
            font-weight: 600
        }

        .hero-items .owl-nav button[type=button] {
            background-color: transparent !important;
        }

        .single-product-item figure img.product-image {
            height: 30vh;
        }

        .header-section {
            padding-left: 0;
            padding-right: 0;
        }



        .inner-header .main-menu ul li .sub-menu {
            height: 30vh;
        }

        .pro-quantity {
            display: flex;
        }

        .pro-quantity input[type=number] {
            border: 1px solid #333;
            width: 40px;
            height: 40px;
            text-align: center;

        }


        .pro-quantity input[type="number"]::-webkit-inner-spin-button,
        .pro-quantity input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .pro-quantity input[type="number"] {
            -moz-appearance: textfield;
            /* Firefox */
        }

        button.quantity {
            border: 1px solid #333;
            background-color: #fff;
            padding: 2px 4px;
            width: 40px;
            height: 40px;
            cursor: pointer;
        }

        button.add,
        button.add a {
            color: #fff;
            background-color: #333;
            border-radius: 10px;
            cursor: pointer;
            width: 150px;
            padding: 8px 12px;
            margin: 15px 0;
        }


        .pd-size-choose {
            margin-bottom: 30px;
        }

        .pd-size-choose .sc-item {
            display: inline-block;
            margin-right: 5px;
        }

        .pd-size-choose .sc-item:last-child {
            margin-right: 0;
        }

        .pd-size-choose .sc-item input {
            position: absolute;
            visibility: hidden;
        }

        .pd-size-choose .sc-item label {
            font-size: 16px;
            color: #252525;
            font-weight: 700;
            height: 40px;
            width: 47px;
            border: 1px solid #ebebeb;
            text-align: center;
            line-height: 40px;
            text-transform: uppercase;
            cursor: pointer;
        }

        .pd-size-choose .sc-item input[type="radio"]:checked+label {
            background-color: #333;
            color: #ffffff;
        }

        .square-radio {
            display: inline-block;
            position: relative;
            width: 40px;
            height: 40px;
            border: 2px solid #e6e6e6;
            border-radius: 50%;
            cursor: pointer;
        }

        .square-radio input[type="radio"] {
            display: none;
        }

        .square-radio span {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Thay đổi màu nền của hình tròn bên trong */
        .square-radio input[type="radio"]:checked+span {
            background-color: var(--color);
        }

        .register-login-section {
            padding-top: 72px;
            padding-bottom: 80px;
        }

        .register-form h2,
        .login-form h2 {
            color: #252525;
            font-weight: 700;
            text-align: center;
            margin-bottom: 35px;
        }

        .register-form form .group-input,
        .login-form form .group-input {
            margin-bottom: 25px;
        }

        .register-form form .group-input label,
        .login-form form .group-input label {
            display: block;
            font-size: 18px;
            color: #252525;
            margin-bottom: 13px;
        }

        .register-form form .group-input input,
        .login-form form .group-input input {
            border: 1px solid #ebebeb;
            height: 50px;
            width: 100%;
            padding-left: 20px;
            padding-right: 15px;
        }

        .register-form form .register-btn,
        .register-form form .login-btn,
        .login-form form .register-btn,
        .login-form form .login-btn {
            width: 100%;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .register-form .switch-login,
        .login-form .switch-login {
            text-align: center;
            margin-top: 22px;
        }

        .register-form .switch-login .or-login,
        .login-form .switch-login .or-login {
            color: #252525;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
        }

        .register-form .switch-login .or-login:before,
        .login-form .switch-login .or-login:before {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            width: 100%;
            background: #9f9f9f;
            content: "";
        }

        .inner-header .header-right .main-menu ul li {
            margin-left: 23px;
        }

        .inner-header .header-right .main-menu {
            margin-right: 150px;
        }

        .inner-header .header-right .main-menu ul li .sub-menu {
            text-align: Center;
            height: 15vh;
        }

        .inner-header .header-right .main-menu ul li .sub-menu button.details {
            background-color: #333;
            margin-bottom: 20px;
        }

        .inner-header .header-right .main-menu ul li .sub-menu button.details a {
            color: white;
        }


        .inner-header .header-right .main-menu ul li .sub-menu button.out {
            background-color: #fff;
            padding: 0 30px;
        }

        .inner-header .header-right .main-menu ul li .sub-menu button.out a {
            color: #333;
        }



        input.site-btn.cupone {
            background-color: #333;
            color: #fff;
            margin-top: 15px;
            font-weight: 700;
            border: 0;
        }

        input.site-btn.cupone:hover {
            transition: .5s;
            background-color: transparent;
            color: #333;
        }

        .select-more {
            border: 1px solid #333;
            padding: 10px;
        }

        a.select-more {
            color: #333;
        }

        .sc-item.out-of-stock label {
            color: red;
            position: relative;
        }

        .sc-item.out-of-stock label .stock-status {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: black;
            opacity: 0.3;
            border-radius: 50%;
            height: 16px;
            font-size: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .product-details {
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-weight: bold;
        }

        .product-info p {
            margin: 0;
            font-weight: 600;
        }

        a.order_back {
            color: #333;
            border: 1px solid #333;
            padding: 3%;
        }

        a.order_back:hover {
            color: white;
            background-color: #333;
            transition: .5s;
        }

        .btn--small {
            padding: 0 10px;
            font-size: .92308em;
            border-width: 2px;
            line-height: 25px;
        }

        .btn--secondary {
            color: #fff;
            background-color: #e55151;
            border-color: #e55151;
        }

        .btn--secondary:hover {
            color: #fff;
            background-color: #111;
            border-color: #111;

        }

        span#quantities {
            display: block;
            font-weight: 600;
        }

        .vertical-line {
            border-left: 2px solid #ccc;
            height: 11vh;
            position: absolute;
        }

        .payment-method ul li {
            margin-left: 30px;
        }

        input#momoButton {
            width: 50px;
            height: 50px;
        }

        section.thanks {
            margin: 120px 0;
        }

        .contact-details a {
            color: #333;
            text-decoration: none;
        }

        /* Styles for the pop-up */
        .popup {
            translate: .5s;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            
        }

        .popup.show {
            opacity: 1;
                visibility: visible;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            display: flex;
            width: 500px;
            height: 300px;
            text-align: center;
            align-items: stretch;
            flex-direction: column;
            justify-content: center;
        }

        .popup-buttons {
            margin-top: 20px;
        }

        .confirm-btn,
        .cancel-btn {
            background: #333;
            border: 1px solid #333;
            color: #fff;
            padding: 10px 20px;
            margin: 10px;
            cursor: pointer;
        }

        .confirm-btn:hover,
        .cancel-btn:hover {
            background-color: transparent;
            color: #333;
            transition: .5s;
        }




        .cancel-btn:active,
        .confirm-btn:active {
            border: none;
        }

        /* Show the pop-up */
        .popup.show {
            display: flex;
        }
    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Search model -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form" action="categories.php" method="get">
                <input type="text" id="search-input" name="search-product" autocomplete="off" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    <header class="header-section">
        <div class="container-fluid">
            <div class="inner-header">
                <div class="logo">
                    <a href="./index.php"><img src="image/logo.png" alt=""></a>
                </div>
                <?php

                // Kiểm tra sự tồn tại của session email hoặc username


                if (isset($_SESSION['user'])) {
                    $c_id = $_SESSION['user'];
                    $email = $c_id['email'];
                    $sql_check = "SELECT * FROM users WHERE email = '$email'";
                    $run_sql_check = executeSingleResult($sql_check);
                    if ($run_sql_check['token'] != NULL) {
                        if ($_SESSION['token'] != $run_sql_check['token']) {
                            unset($_SESSION['token']);
                            unset($_SESSION['user']);
                            echo '<script>
                // Kiểm tra xem pop-up đã được hiển thị trước đó hay chưa
                if (!localStorage.getItem("popupDisplayed")) {
                    // Hiển thị pop-up
                    var popup = document.createElement("div");
                    popup.id = "popup";
                    popup.innerHTML = "<div class=\"popup-content\"><h3>Tài khoản của bạn đang được sử dụng bởi người khác.</h3><p>Vui lòng đăng nhập lại để đảm bảo an toàn tài khoản.</p></div>";
                    document.body.appendChild(popup);

                    // Đóng pop-up khi người dùng nhấp vào nút "Đăng nhập lại" hoặc bất kỳ vị trí nào bên ngoài pop-up
                    popup.addEventListener("click", function(e) {
                        if (e.target.id === "popup" || e.target.className === "popup-content") {
                            document.body.removeChild(popup);
                            localStorage.removeItem("popupDisplayed");
                            window.location.href = "signin.php";
                        }
                    });

                    // Lưu trạng thái đã hiển thị pop-up vào localStorage
                    localStorage.setItem("popupDisplayed", true);
                }
            </script>';
                        }
                    }
                    // Hiển thị phần header-right
                ?>
                    <div class="header-right">
                        <div class="main-menu mobile-menu">
                            <ul>
                                <li>
                                    <img src="assets/img/icons/search.png" alt="" class="search-trigger">
                                </li>



                                <li><?php echo $c_id['username'] ?></li>

                                <li>
                                    <a href="./details.php"><img src="assets/img/icons/man.png" alt="">
                                    </a>
                                    <ul class="sub-menu">
                                        <li><button class="details"><a href="./details.php">My account</a></button></li>
                                        <li><button class="out"><a href="./logout.php">Log out</a></button></li>

                                    </ul>
                                </li>
                                <li>
                                    <a href="shopping-cart.php">
                                        <img src="assets/img/icons/bag.png" alt="">
                                        <?php
                                        $username = $c_id['username'];
                                        $email = $c_id['email'];
                                        $cart = "SELECT count(pid) as amount FROM cart WHERE c_id = '$email' or c_id = '$username' ";
                                        $count_cart = executeSingleResult($cart);

                                        ?>
                                        <span>
                                            <?php echo $count_cart['amount']; ?>
                                        </span>
                                    </a>
                                </li>
                            </ul>

                        </div>

                    </div>
                <?php
                } else {
                ?>
                    <div class="user-access">
                        <img src="assets/img/icons/search.png" style="margin-right:30px;" alt="" class="search-trigger">
                        <a href="register.php">Register</a>
                        <a href="signin.php" class="in">Sign in</a>
                    </div>
                <?php
                }
                ?>
                <nav class="main-menu mobile-menu">
                    <ul>
                        <li><a class="active" href="./index.php">Home</a></li>
                        <li><a href="./categories.php">Shop</a>
                            <ul class="sub-menu">
                                <?php
                                foreach ($product as $p) {
                                ?>
                                    <li><a href="categories.php?cat_id=<?php echo $p['cat_id'] ?>"><?php echo $p['cat_name'] ?></a></li>
                                <?php
                                }

                                ?>
                            </ul>
                            <ul class="sub-menu" style="margin-left:100px">
                                <?php
                                foreach ($product_categories as $c) {
                                ?>
                                    <li><a href="categories.php?p_cat_id=<?php echo $c['p_cat_id'] ?>"><?php echo $c['p_cat_name'] ?></a></li>
                                <?php
                                }

                                ?>
                            </ul>
                        </li>
                        <li><a href="./about-us.php">About</a></li>
                        <li><a href="./contact.php">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <!-- Header Info Begin -->
    <!-- <div class="header-info">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="header-item">
                        <img src="assets/img/icons/delivery.png" alt="">
                        <p>Free shipping on orders over $30 in USA</p>
                    </div>
                </div>
                <div class="col-md-4 text-left text-lg-center">
                    <div class="header-item">
                        <img src="assets/img/icons/voucher.png" alt="">
                        <p>20% Student Discount</p>
                    </div>
                </div>
                <div class="col-md-4 text-left text-xl-right">
                    <div class="header-item">
                        <img src="assets/img/icons/sales.png" alt="">
                        <p>30% off on dresses. Use code: 30OFF</p>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Header Info End -->
    <!-- Header End -->