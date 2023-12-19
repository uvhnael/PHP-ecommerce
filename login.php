<?php
include("includes/db.php");
if (isset($_COOKIE['user'])) {
    header('location: index.php');
}
if (isset($_POST) & !empty($_POST)) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $sql = "SELECT * FROM customers WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['active'] == 1) {
            $cookie_name = "user";
            $cookie_value = $row['id'];
            $user_name = $row['last_name'] . " " . $row['first_name'];
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            setcookie("user_name", $user_name, time() + (86400 * 30), "/");
            header('location: index.php');
        } else {
            $fmsg = "Please activate your account.";
        }
    } else {
        $fmsg = "Invalid Login Credentials.";
    }
}
include("includes/header.php");
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="card" style="margin-top: 50px;">
                <div class="card-header">
                    <h5 class="text-center">Login</h5>
                </div>

                <div class="card-body">
                    <form method="post">
                        <div class="form-group mb-3">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                        <div class="form-group mb-3">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <?php if (isset($fmsg)) { ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?>
                            </div><?php } ?>
                        <div class="d-grid gap-2 col-12 mx-auto">
                            <button type="submit" class="btn btn-secondary ">Login</button>
                        </div>
                    </form>
                    <!-- </div> -->
                    <!-- login api -->
                    <!-- <div class="card-footer"> -->
                    <div class="row p-2">
                        <div>
                            Forgot Password or can't login?
                            <a href="forgot-password.php" class="text-decoration-none">Reset Password</a>
                        </div>
                        <div>
                            Don't have an account?
                            <a href="register.php" class="text-decoration-none">Register</a>
                        </div>
                    </div>
                    <!-- facebook login -->
                    <hr class>

                    <div class="d-grid gap-2 col-12 mx-auto">
                        <a href="api_google.php" class="btn btn-danger btn-block"><i class="fa-brands fa-google"></i>
                            Google</a>
                        <a href="api_facebook.php" class="btn btn-primary btn-block"><i class="fa-brands fa-facebook"></i> Facebook</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include("includes/footer.php");
?>