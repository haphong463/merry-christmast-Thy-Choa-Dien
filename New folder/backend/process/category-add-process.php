    <?php
    require_once '../../db/dbhelper.php';
    if (isset($_POST['create-cat'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
    }
    $description = addslashes($description);


    $sql = "INSERT INTO category (cat_name, cat_desc) VALUES ('$name', '$description')";
    execute($sql);
    header('Location: ../category.php');
