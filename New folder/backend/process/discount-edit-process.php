<?php
require_once '../../db/dbhelper.php';
if (isset($_POST['update-coupon'])) {
    $id = $_POST['id'];
    $discount = $_POST['discount'];
    $expirationDays = $_POST['date'];
    $expirationDate = date('Y-m-d', strtotime("+$expirationDays days"));

    if (isset($_FILES["banner"]) && $_FILES['banner']['name'] != '') {

        $sql = "SELECT banner FROM discount WHERE id = $id";
        $slide = executeSingleResult($sql);
        $oldSlide = $slide['banner'];
        if (!empty($oldSlide) && file_exists('../../' . $oldSlide)) {
            unlink('../../' . $oldSlide);
        }


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
            echo '<script>alert("Only JPG, AVIF, PNG, Webp files are allowed")</script>';
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
    } else {
        $sql = "SELECT banner FROM discount WHERE id = $id";
        $oldImage = executeSingleResult($sql)['banner'];
        $image = $oldImage;
    }

    $sql = "UPDATE discount SET discount = '$discount',
     expiration_date = '$expirationDate', banner = '$image'";
    execute($sql);

    header('Location: ../discount.php');
}
