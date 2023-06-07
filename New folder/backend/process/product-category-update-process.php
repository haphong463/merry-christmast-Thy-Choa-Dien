<?php
require_once ('../../db/dbhelper.php');

if(isset($_POST['update-pro-cat'])){
    $id = $_POST['p_cat_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
}

// Sử dụng hàm addslashes để tránh xảy ra lỗi khi nhập dấu chấm
$description = addslashes($description);

$sql = "UPDATE product_category SET p_cat_name = '$name', p_cat_desc = '$description' WHERE p_cat_id = $id";
execute($sql);
header('Location: ../type.php');
