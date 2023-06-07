<?php
require_once '../../db/dbhelper.php';

function generateCouponCode()
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $couponCode = '';
    $length = 5;

    // Tạo ngẫu nhiên mã coupon
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($characters) - 1);
        $couponCode .= $characters[$randomIndex];
    }

    return $couponCode;
}
if (isset($_POST['create-coupon'])) {
    $coupon = generateCouponCode();
    $discount = $_POST['discount'];
    $quantity = $_POST['quantity'];
    $startDate = date('Y-m-d');
    $expirationDays = $_POST['date'];
    $expirationDate = date('Y-m-d', strtotime("+$expirationDays days"));

    $target_dir = "../../image/discount/";
    $target_file = $target_dir . basename($_FILES["banner"]["name"]);
    $upload_ok = 1;
    $image_file_type =
        strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra định dạng file ảnh
    if (
        $image_file_type != "jpg" && $image_file_type != "png"
        && $image_file_type != "avif" && $image_file_type != "webp"
    ) {
        echo 'Only JPG, JPEG, PNG, GIF files are allowed';
        $upload_ok = 0;
    }

    // Kiểm tra tên file trùng lặp
    if (file_exists($target_file)) {
        echo 'The file name already exits. Pls change your file name!';
        $upload_ok = 0;
    }

    if ($_FILES['banner']['size'] > 2097152) {
        echo 'The image file size cannot be greater than 2mb';
        $upload_ok = 0;
    }

    // Lưu tệp tin ảnh
    if ($upload_ok == 1) {
        move_uploaded_file($_FILES["banner"]["tmp_name"], $target_file);
        //echo 'upload successfully!';     
    }
    $image = 'image/discount/' . $_FILES["banner"]["name"];


    $sql = "INSERT INTO discount (coupon_code, discount, quantity, startDate, expiration_date, banner) VALUES ('$coupon', '$discount','$quantity', '$startDate', '$expirationDate', '$image')";
    // echo $sql;
    execute($sql);
    // header('Location: ../discount.php');
}
