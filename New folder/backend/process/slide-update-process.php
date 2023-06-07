<?php
require_once '../../db/dbhelper.php';
if (isset($_POST['update-slide'])) {
    $id = $_POST['id'];
    $year = $_POST['year'];
    $heading = $_POST['heading'];


    if (isset($_FILES["slide"]) && $_FILES['slide']['name'] != '') {

        $sql = "SELECT image FROM slider WHERE id = $id";
        $slide = executeSingleResult($sql);
        $oldSlide = $slide['image'];
        if (!empty($oldSlide) && file_exists('../../' . $oldSlide)) {
            unlink('../../' . $oldSlide);
        }


        $target_dir = "../../image/slider/";
        $target_file = $target_dir . basename($_FILES["slide"]["name"]);
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

        if ($_FILES['slide']['size'] > 2097152) {
            echo 'The image file size cannot be greater than 2mb';
            $upload_ok = 0;
        }

        // Lưu tệp tin ảnh
        if ($upload_ok == 1) {
            move_uploaded_file($_FILES["slide"]["tmp_name"], $target_file);
            //echo 'upload successfully!';     
        }
        $image = 'image/slider/' . $_FILES["slide"]["name"];
    } else {
        $sql = "SELECT image FROM slider WHERE id = $id";
        $oldImage = executeSingleResult($sql)['image'];
        $image = $oldImage;
    }
}



$sql = "UPDATE slider SET year = '$year', heading = '$heading', image = '$image' WHERE id = $id";
execute($sql);
header('Location: ../slider.php');
