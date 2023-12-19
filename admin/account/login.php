<?php
session_start();
require_once('../includes/db.php');
if (isset($_POST) & !empty($_POST)) {
   $email = $_POST['email'];
   $pass = $_POST['password'];
   $pass = md5($pass);
   $sql = "SELECT * FROM staffs WHERE email='$email' AND password='$pass'";
   $result = mysqli_query($conn, $sql);
   $count = mysqli_num_rows($result);
   $staff = mysqli_fetch_assoc($result);
   if ($count == 1) {
      $sql = "SELECT id FROM staffs WHERE email='$email'";
      $result = mysqli_query($conn, $sql);
      $_SESSION['id'] = mysqli_fetch_assoc($result)['id'];
      $_SESSION['email'] = $email;


      $staff_id = $_SESSION['id'];

      $sql = "SELECT * FROM staff_roles INNER JOIN roles ON staff_roles.role_id = roles.id WHERE staff_roles.staff_id = $staff_id";
      $result = mysqli_query($conn, $sql);
      $role = mysqli_fetch_assoc($result);

      $_SESSION['role'] = $role['privileges'];
      $_SESSION['role_name'] = $role['role_name'];

      $sql = "UPDATE staffs SET active=1 WHERE id='$staff_id'";
      mysqli_query($conn, $sql);

      if ($staff['password'] == md5('1')) {
         header("location:../account/change_password.php");
      } else {
         header("location:../index.php");
      }
   } else {
      $fmsg = "Invalid Login Credentials";
   }
}
include("../includes/header.php");
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
                  <button type="submit" class="btn btn-primary">Login</button>
               </form>
            </div>
         </div>
      </div>
   </div>
   <?php include("../includes/footer.php"); ?>