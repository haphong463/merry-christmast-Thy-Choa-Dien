<?php
require '../../db/dbhelper.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM discount WHERE id = $id";
    execute($sql);
    header('Location: ../discount.php');
}
