<?php
require('../../db/dbhelper.php');
$id = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$sql2 = "SELECT * FROM category where id = $id";
$category = executeSingleResult($sql2);
unlink('../' . $category['image']);

$sql = "DELETE FROM category where id = $id";
execute($sql);
header('Location: ../category.php');
