<?php include('layout/header.php') ?>
<?php
require_once('db/dbhelper.php');
if (isset($_SESSION['user'])) {
    echo '<script language="javascript">window.location="index.php";</script>';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $passwordInput = trim($_POST['password']);
    $email = trim($_POST['email']);
    $date = trim($_POST['date']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);
    $errors = array();

    $existingEmail = executeSingleResult("SELECT * FROM users WHERE email = '$email'");
    $existingUsername = executeSingleResult("SELECT * FROM users WHERE username = '$username'");

    if (empty($username) || strlen($username) < 3 || strlen($username) > 20) {
        $errors['username'] = "Username must be between 3 and 20 characters long";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid Email format";
    }

    if (empty($passwordInput) || strlen($passwordInput) < 3 || strlen($passwordInput) > 20) {
        $errors['password'] = "Password must be between 3 and 20 characters long";
    }


    if (empty($errors)) {
        // Kiểm tra email đã tồn tại trong cơ sở dữ liệu hay chưa
        $existingEmail = executeSingleResult("SELECT * FROM users WHERE email = '$email'");
        if ($existingEmail) {
            $errors['email'] = "Email already exists";
        } elseif ($existingUsername) {
            $errors['username'] = "Username already exists";
        } else {
            $hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);
            // Chuyển đổi định dạng ngày tháng dd/mm/yyyy thành yyyy-mm-dd
            $dateParts = explode('/', $date);
            $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
            $sql = "INSERT INTO users (username, full_name, password, email, date_of_birth, address, contact) 
                    VALUES ('$username','$fullname', '$hashedPassword', '$email', '$formattedDate', '$address', '$contact')";

            if (execute($sql)) {
                echo '<script language="javascript">alert("Successfully registered!"); window.location="signin.php";</script>';
            } else {
                echo '<script language="javascript">alert("Registration failed!"); window.location="register.php";</script>';
            }
        }
    }
}
?>



<section class="page-add">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="page-breadcrumb">
                    <h2>SIGN UP<span>.</span></h2>
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
                    <form action="register.php" method="post" enctype="multipart/form-data" id="logform">
                        <div class="row">
                            <div class="group-input col-md-6">
                                <label for="fullname">Full Name *</label>
                                <input type="text" id="fullname" name="fullname" autocomplete="off" placeholder="enter your full name..." required>
                            </div>
                            <div class="group-input col-md-6">
                                <label for="date">Date of birth *</label>
                                <input type="text" id="date" name="date" required autocomplete="off" placeholder="dd/mm/yyyy" oninput="restrictAndValidateDate(this)">
                            </div>
                        </div>
                        <div class="group-input">
                            <label for="con">Username *</label>
                            <input type="text" id="con" name="username" autocomplete="off" placeholder="enter your username..." required>
                            <?php if (isset($errors['username'])) : ?>
                                <span class="error"><?php echo $errors['username']; ?></span>
                            <?php endif; ?>
                            <?php if (isset($existingUsername)) : ?>
                                <span class="error">Username already exists</span>
                            <?php endif; ?>
                        </div>
                        <div class="group-input">
                            <label for="email">Email *</label>
                            <input type="text" id="email" name="email" autocomplete="off" placeholder="enter your email..." required>
                            <?php if (isset($errors['email'])) : ?>
                                <span class="error"><?php echo $errors['email']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="group-input">
                            <label for="pass">Password *</label>
                            <input type="password" id="pass" name="password" autocomplete="off" placeholder="enter your password..." required>
                            <?php if (isset($errors['password'])) : ?>
                                <span class="error"><?php echo $errors['password']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="group-input">
                            <label for="con-pass">Address *</label>
                            <input type="text" id="con-pass" name="address" autocomplete="off" placeholder="enter your address..." required>
                        </div>
                        <div class="group-input">
                            <label for="con">Contact *</label>
                            <input type="text" id="con" name="contact" autocomplete="off" placeholder="enter your phone number.... (10-11 digits)" required>
                        </div>
                        <button type="submit" class="site-btn register-btn" name="register">REGISTER</button>
                    </form>
                    <div class="switch-login">
                        <a href="signin.php" class="or-login">Or Login | </a>
                        <a href="forgotPassword.php" class="or-login">Forgot Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function restrictAndValidateDate(input) {
        var value = input.value;
        value = value.replace(/\D/g, '');

        // Kiểm tra giá trị đầu vào
        if (value.length > 8) {
            value = value.slice(0, 8);
        }

        // Thêm dấu '/' sau ngày và tháng
        if (value.length > 2 && value.charAt(2) !== '/') {
            value = value.slice(0, 2) + '/' + value.slice(2);
        }
        if (value.length > 5 && value.charAt(5) !== '/') {
            value = value.slice(0, 5) + '/' + value.slice(5);
        }

        input.value = value;
    }
</script>





<?php include('layout/footer.php') ?>