<?php
require_once ('../../db/dbhelper.php');

if(isset($_POST['update-cat'])){
    $id = $_POST['cat_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
}

// Sử dụng hàm addslashes để tránh xảy ra lỗi khi nhập dấu chấm
$description = addslashes($description);

$sql = "UPDATE category SET cat_name = '$name', cat_desc = '$description' WHERE cat_id = $id";
execute($sql);
header('Location: ../category.php');
