<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

include_once('../includes/db.php');


if (isset($_POST) & !empty($_POST)) {
    $staff_id = $_POST['staff_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    if (isset($_FILES['profile_img']) & !empty($_FILES['profile_img']['name'])) {
        $target_dir = "../profile_img/";
        $target_file = $target_dir . basename($_FILES["profile_img"]["name"]);
        $uploadOk = 1;

        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["profile_img"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $fmsg = "File is not an image.";
                $uploadOk = 0;
            }
        }

        if (file_exists($target_file)) {
            $target_file = $target_dir . time() . basename($_FILES["profile_img"]["name"]);
        }

        if ($_FILES["profile_img"]["size"] > 500000) {
            $uploadOk = 0;
        }

        $allowed_types = array('jpg', 'png', 'jpeg', 'gif');
        $file_ext = pathinfo($target_file, PATHINFO_EXTENSION);
        if (in_array(strtolower($file_ext), $allowed_types)) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Save file
        if ($uploadOk == 0) {
            $fmsg = "Sorry, your file was not uploaded.";
            $profile_img = "";
        } else {
            if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
                $profile_img = $target_file;
            }
        }
        $sql = "UPDATE staffs SET first_name='$first_name', last_name='$last_name', phone_number='$phone_number', email='$email', profile_img='$profile_img' WHERE id=$staff_id";
        $res = mysqli_query($conn, $sql);
    } else {
        $sql = "UPDATE staffs SET first_name='$first_name', last_name='$last_name', phone_number='$phone_number', email='$email' WHERE id=$staff_id";
        $res = mysqli_query($conn, $sql);
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';

$staff_id = $_SESSION['id'];

$sql = "SELECT * FROM staffs WHERE id=$staff_id";
$res = mysqli_query($conn, $sql);
$staff = mysqli_fetch_assoc($res);

?>

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <?php if (isset($fmsg)) {
                echo $fmsg;
            } ?>
            <h2>Your Profile</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="staff_id">Staff ID</label>
                    <input type="text" readonly class="form-control" id="staff_id" name="staff_id" placeholder="Staff ID" value="<?php echo $staff['id']; ?>">
                </div>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo $staff['first_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo $staff['last_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" value="<?php echo $staff['phone_number']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="tel" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $staff['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="profile_img">Profile Image</label>
                    <input type="file" class="form-control" id="profile_img" name="profile_img">
                </div>
                <div class="d-grid gap-2 col-4 mx-auto pt-4">
                    <button type="submit" class="btn btn-primary ">Update</button>
                </div>

            </form>
        </div>
    </div>
</div>