<?php include('layout/header.php');
if (isset($_SESSION['user'])) {
    echo '<script>window.location.href = "index.php";</script>';
}

?>

<?php
// var_dump($_SESSION['user']);
// die();
include_once('db/dbhelper.php');
require_once 'vendor/autoload.php'; // Import thư viện firebase/php-jwt

use Firebase\JWT\JWT;

// Tạo mã token
function createToken($secretKey, $expirationTimeMinutes)
{
    // Lấy thời gian hiện tại
    $currentTime = time();
    // Tính thời gian hết hạn
    $expirationTime = $currentTime + ($expirationTimeMinutes * 60);

    // Tạo payload (dữ liệu chứa trong token)
    $payload = array(
        'email' => '1234567890',  // Thông tin cần lưu trữ trong token
        'exp' => $expirationTime  // Thời gian hết hạn của token
    );

    // Tạo mã token với secret key
    $jwtBuilder = new JWT();
    $token = $jwtBuilder->encode($payload, $secretKey, 'HS256');

    return $token;
}

// Sử dụng ví dụ:
if (isset($_POST['signin'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $err = [];

    $sql = "SELECT * FROM users WHERE email= '$email'";
    $checkEmail = executeSingleResult($sql); // Retrieve a single row
    // $sql = "SELECT email FROM user WHERE email = '$email'";
    // $result =  executeSingleResult($sql); 
    // $email = $result['email']; 
    // echo $email;
    if (empty($email)) {
        $err['email'] = 'Email is required';
    }
    // var_dump($checkEmail);
    if (empty($checkEmail)) {
        $err['email'] = 'Email does not exists!';
    } else {
        if (empty($password)) {
            $err['password'] = 'Please enter your password to sign in!';
        }
        $checkPass = password_verify($password, $checkEmail['password']);
        // var_dump($checkEmail);
        // var_dump($checkPass);
        if ($checkPass) {
            if ($checkEmail["status"] != 1) {
                $userId = $checkEmail['email'];
                $sql = "SELECT token FROM users WHERE email = '$userId'";
                $currentToken = executeSingleResult($sql);
                $secretKey = 'your_secret_key';
                $expirationTimeMinutes = 60; // Thời gian hết hạn token: 60 phút
                $token = createToken($secretKey, $expirationTimeMinutes);
                $_SESSION['token'] = $token;
                $sql = "UPDATE users SET token = '$token' WHERE email = '$userId'";
                execute($sql);
                $_SESSION['user'] = $checkEmail;
                echo '<script>window.location.href = "index.php";</script>';
            } else {
                echo "<script>alert('you made a mistake with our website, you are banned from logging in for 1 day')</script>";
            }
        } else {
            $err['password'] = 'Incorrect password!';
        }
    }
}
?>


<section class="page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2>SIGN IN<span>.</span></h2>
                </div>
            </div>
            <div class="col-lg-8">
                <img src="img/add.jpg" alt="">
            </div>
        </div>
    </div>
</section>

<div class="register-login-section spad">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="register-form">
                    <form action="signin.php" method="post" enctype="multipart/form-data" id="logform">
                        <div class="group-input">
                            <label for="email">Email/Username *</label>
                            <span class="error"><?php echo isset($err['email']) ? $err['email'] : ''; ?></span>
                            <input type="text" id="email" autocomplete="off" name="email">
                        </div>
                        <div class="group-input">
                            <label for="pass">Password *</label>
                            <input type="password" id="pass" autocomplete="off" name="password">
                            <span class="error"><?php echo isset($err['password']) ? $err['password'] : ''; ?></span>
                        </div>
                        <button type="submit" class="site-btn login-btn" name="signin">SIGN IN</button>
                    </form>
                    <div class="switch-login">
                        <a href="register.php" class="or-login">Or Register</a>
                        |
                        <a href="forgotPassword.php" class="or-login">Forgot Password ?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('layout/footer.php') ?>