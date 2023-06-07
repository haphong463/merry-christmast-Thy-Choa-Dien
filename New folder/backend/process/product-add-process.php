    <?php
    require_once '../../db/dbhelper.php';
    if (isset($_POST['create-product'])) {
        $name = $_POST['name'];
        $category = $_POST['cat_id'];
        $type = $_POST['p_cat_id'];
        $description = $_POST['desc'];
        $color = $_POST['color'];
        $sizes = $_POST['size'];
        $quantity =  $_POST['quantity'];
        $price = number_format($_POST['price'], '2', '.');
        $keyword = $_POST['keyword'];
        $desc = addslashes($description);

        // echo '<pre>';
        // var_dump($name, $category, $type, $description, $color, $sizes, $quantity, $price, $keyword, $desc);
        // echo '</pre>';

        // Kiểm tra số lượng ảnh tải lên không vượt quá 9
        // if (is_array($images['name'])) {
        //     if (count($images['name']) > 9) {
        //         echo 'You can upload up to 9 images only';
        //         exit();
        //     }
        // } else {
        //     $images['name'] = [$images['name']];
        //     $images['type'] = [$images['type']];
        //     $images['tmp_name'] = [$images['tmp_name']];
        //     $images['error'] = [$images['error']];
        //     $images['size'] = [$images['size']];
        // }

        $sql = "INSERT INTO product (cat_id, p_cat_id, name, price, color, description, created_at)
                VALUES ($category, $type, '$name', $price, '$color', '$desc', NOW())";
        execute($sql);

        $sql_max = "SELECT max(pid) as maxID FROM product";
        $result = executeSingleResult($sql_max);
        $product_id = $result['maxID'];


        $takeid = $product_id;
        include('uploadImage.php');

        // chuỗi -> dạng an toàn
        $keyword = addslashes($keyword);

        $variant_values = array();
        foreach ($sizes as $index => $size) {
            $qty = isset($quantity[$index]) ? intval($quantity[$index]) : 0;
            $variant_values[] = "($product_id, '$size', $qty, '$keyword')";
        }
        if (!empty($variant_values)) {
            $sql_size = "INSERT INTO product_variant (pid, size, quantity, keyword) VALUES " . implode(', ', $variant_values);
            execute($sql_size);
        }



        header('location: ../product.php');
    }
