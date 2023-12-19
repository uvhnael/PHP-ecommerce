<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 3 && $role_name != 'Staff Manager') {
        header('location: ../index.php');
    }
}
include_once('../includes/db.php');


if (isset($_POST) & !empty($_POST)) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = md5('1');
    $active = 0;
    $role = $_POST['role'];
    $created_by = $_SESSION['id'];
    if (isset($_FILES['profile_img']) & !empty($_FILES['profile_img'])) {
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
            $target_file = $target_dir . uniqid() . "_" . basename($_FILES["profile_img"]["name"]);
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
    }

    $sql = "INSERT INTO staffs (first_name, last_name, phone_number, email, password, active, created_by, profile_img) VALUES ('$first_name', '$last_name', '$phone_number', '$email', '$password', '$active', '$created_by', '$profile_img')";
    $res = mysqli_query($conn, $sql);

    $staff_id = mysqli_insert_id($conn);

    $sql = "INSERT INTO staff_roles (staff_id, role_id) VALUES ('$staff_id', '$role')";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        header("Location: staffs.php");
    } else {
        $fmsg = "Failed to Delete Data.";
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="container section-padding">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Delete Staff account</h2>
            <?php if (isset($fmsg)) {
                echo $fmsg;
            } ?>

            <form method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number">
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-select" aria-label="Default select example" name="role">
                        <option selected>Select Role</option>
                        <?php
                        $sql = "SELECT * FROM roles";
                        $res = mysqli_query($conn, $sql);
                        while ($r = mysqli_fetch_assoc($res)) {
                        ?>
                            <option value="<?php echo $r['id']; ?>"><?php echo $r['role_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="profile_img" name="profile_img">
                </div>
                <div class="d-grid gap-2 col-4 mx-auto pt-4">
                    <button type="submit" class="btn btn-success ">Add staff account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>