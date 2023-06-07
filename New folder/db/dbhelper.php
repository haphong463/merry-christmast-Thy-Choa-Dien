<?php
$mysql_hostname = 'localhost';
$mysql_user = 'root';
$mysql_password = '';
$mysql_database = 'clothing-project';

function execute($sql)
{
    global $mysql_hostname, $mysql_user, $mysql_password, $mysql_database;
    $con = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

    // Kiểm tra xem kết nối có thành công không
    if (!$con) {
        echo 'Lỗi kết nối cơ sở dữ liệu: ' . mysqli_connect_error();
        return false;
    }

    // Thực thi truy vấn SQL
    $result = mysqli_query($con, $sql);

    // Kiểm tra xem truy vấn có được thực thi thành công không
    if (!$result) {
        echo 'Lỗi thực thi truy vấn: ' . mysqli_error($con);
        mysqli_close($con);
        return false;
    }

    // Đóng kết nối
    mysqli_close($con);
    return true;
}

function executeResult($sql)
{
    global $mysql_hostname, $mysql_user, $mysql_password, $mysql_database;
    $con = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

    // Kiểm tra xem kết nối có thành công không
    if (!$con) {
        echo 'Lỗi kết nối cơ sở dữ liệu: ' . mysqli_connect_error();
        return [];
    }

    // Thực thi truy vấn SQL
    $result = mysqli_query($con, $sql);
    $data = [];

    // Kiểm tra xem truy vấn có được thực thi thành công không
    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = $row;
        }
    } else {
        echo 'Lỗi thực thi truy vấn: ' . mysqli_error($con);
    }

    // Đóng kết nối
    mysqli_close($con);

    return $data;
}

function executeSingleResult($sql)
{
    global $mysql_hostname, $mysql_user, $mysql_password, $mysql_database;
    $con = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

    // Kiểm tra xem kết nối có thành công không
    if (!$con) {
        echo 'Lỗi kết nối cơ sở dữ liệu: ' . mysqli_connect_error();
        return null;
    }

    // Thực thi truy vấn SQL
    $result = mysqli_query($con, $sql);
    $row = null;

    // Kiểm tra xem truy vấn có được thực thi thành công không
    if ($result) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        echo 'Lỗi thực thi truy vấn: ' . mysqli_error($con);
    }

    // Đóng kết nối
    mysqli_close($con);

    return $row;
}
