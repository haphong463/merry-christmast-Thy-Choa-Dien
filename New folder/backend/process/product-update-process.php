<?php
require_once '../../db/dbhelper.php';

if (isset($_POST['update-product'])) {
    $id = $_POST['pid'];
    $name = $_POST['name'];
    $category = $_POST['cat_id'];
    $type = $_POST['p_cat_id'];
    $description = addslashes($_POST['desc']);
    $color = $_POST['color'];
    $sizes = $_POST['size'];
    $quantity = $_POST['quantity'];
    $price = number_format($_POST['price'], '2', '.');
    $keyword = $_POST['keyword'];


    $delete_variant_query = "DELETE FROM product_variant WHERE pid = $id";
    execute($delete_variant_query);


    $sql = "UPDATE product SET cat_id = $category, p_cat_id = $type, name = '$name',
     price = $price, color = '$color', description = '$description', updated_at = NOW() WHERE pid = $id";
    execute($sql);


    // Kiểm tra xem người dùng đã tải lên ảnh mới hay chưa
    if (!empty($_FILES['image']['name'][0])) {
        // Lấy các đường dẫn hình ảnh cũ từ cơ sở dữ liệu
        $sql_image_old = "SELECT pi.image_path, pt.thumbnail FROM product_image AS pi INNER JOIN product_thumbnail AS pt ON pi.pid = pt.pid WHERE pi.pid = $id";
        $result_image_old = executeResult($sql_image_old);

        // Tạo một mảng lưu trữ các đường dẫn của các hình ảnh cũ
        $old_image_paths = array();
        foreach ($result_image_old as $image_old) {
            $old_image_paths[] = ($image_old['image_path']);
            $old_image_paths[] = ($image_old['thumbnail']);
        }

        // Xóa các hình ảnh cũ trong thư mục và cơ sở dữ liệu
        foreach ($old_image_paths as $old_image_path) {
            $file_path = '../../' . $old_image_path;
            if (file_exists($file_path)) {
                unlink($file_path); // Xóa ảnh thực tế trong thư mục
            }
            $sql_delete_image = "DELETE FROM product_image WHERE image_path = '$old_image_path'";
            execute($sql_delete_image); // Xóa ảnh trong cơ sở dữ liệu

            $sql_delete_thumbnail = "DELETE FROM product_thumbnail WHERE thumbnail = '$old_image_path'";
            execute($sql_delete_thumbnail); // Xóa ảnh trong cơ sở dữ liệu
        }
    }
    $takeid = $id;
    include('uploadImage.php');



    // Thêm thông tin về quantity (màu sắc và số lượng)
    $keyword = addslashes($keyword);

    $variant_values = array();
    foreach ($sizes as $index => $size) {
        $qty = isset($quantity[$index]) ? intval($quantity[$index]) : 0;
        $variant_values[] = "($id, '$size', $qty, '$keyword')";
    }
    if (!empty($variant_values)) {
        $sql_size = "INSERT INTO product_variant (pid, size, quantity, keyword) VALUES " . implode(', ', $variant_values);
        execute($sql_size);
    }
}
header('location: ../product.php');
