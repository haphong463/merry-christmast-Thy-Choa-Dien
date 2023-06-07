<?php
require_once '../../db/dbhelper.php';
if (isset($_POST['create-pro-cat'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
}
$description = addslashes($description);


$sql = "INSERT INTO product_category (p_cat_name, p_cat_desc) VALUES ('$name', '$description')";
execute($sql);
header('Location: ../type.php');
