<?php
session_start();
include("includes/db.php");
include("includes/header.php");

if (isset($_POST) & !empty($_POST)) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $sql = "INSERT INTO `customers` (first_name, last_name, phone_number, email, password) VALUES ('$first_name', '$last_name', '$phone_number', '$email', '$password')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("location: index.php");
    } else {
        $fmsg = "User Registration Failed";
    }
}
?>


<div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="card" style="margin-top: 50px;">
                <div class="card-header">
                    <h5 class="text-center">Register</h5>
                </div>

                <div class="card-body">
                    <form method="post">
                        <div class="form-group mb-3">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" id="first_name"
                                placeholder="First Name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" id="last_name"
                                placeholder="Last Name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" id="phone_number"
                                placeholder="Phone Number" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Enter email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Password" required>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" id="strengthbar" role="progressbar" aria-valuenow="75"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="exampleInputPassword1">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control"
                                placeholder="Confirm Password" required>
                        </div>
                        <div id="passwordMatch"></div>


                        <?php if (isset($fmsg)) { ?><div class="alert alert-danger" role="alert">
                            <?php echo $fmsg; ?>
                        </div><?php } ?>
                        <div class="d-grid gap-2 col-12 mx-auto">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const passwordField = document.getElementById('password');
const confirmPasswordField = document.getElementById('confirmPassword');
const passwordMatch = document.getElementById('passwordMatch');
const strengthbar = document.getElementById("strengthbar");

// Event listener for password input
passwordField.addEventListener('input', () => {
    const password = passwordField.value;
    const strength = calculatePasswordStrength(password);
    updateStrengthIndicator(strength);
});

// Event listener for confirm password input
confirmPasswordField.addEventListener('input', () => {
    const password = passwordField.value;
    const confirmPassword = confirmPasswordField.value;
    const passwordsMatch = password === confirmPassword;
    updateMatchIndicator(passwordsMatch);
});

// Function to calculate password strength (you can customize this)
function calculatePasswordStrength(password) {
    var strength = 0;
    if (password.match(/[a-z]+/)) {
        strength += 1;
    }
    if (password.match(/[A-Z]+/)) {
        strength += 1;
    }
    if (password.match(/[0-9]+/)) {
        strength += 1;
    }
    if (password.match(/[$@#&!]+/)) {
        strength += 1;

    }

    switch (strength) {
        case 0:
            return 0;
            break;

        case 1:
            return 25;
            break;

        case 2:
            return 50;
            break;

        case 3:
            return 75;
            break;

        case 4:
            return 100;
            break;
    }
}

// Function to update strength indicator
function updateStrengthIndicator(strength) {
    // add style width to indicator
    strengthbar.setAttribute("style", "width: " + strength + "%");
    // change color indicator
    if (strength <= 25) {
        strengthbar.setAttribute("class", "progress-bar bg-danger");
    } else if (strength <= 50) {
        strengthbar.setAttribute("class", "progress-bar bg-warning");
    } else if (strength <= 75) {
        strengthbar.setAttribute("class", "progress-bar bg-info");
    } else {
        strengthbar.setAttribute("class", "progress-bar bg-success");
    }
}

// Function to update match indicator
function updateMatchIndicator(match) {
    if (!match)
        confirmPasswordField.setCustomValidity('Passwords do not match');
    else
        confirmPasswordField.setCustomValidity('');
}
</script>
<?php
include("includes/footer.php");
?>