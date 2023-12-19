<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left" style="width:200px;" id="mySidebar">
    <?php
    if (isset($_SESSION['email']) & !empty($_SESSION['email'])) {
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM staffs WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $role = $_SESSION['role'];
        $role_name = $_SESSION['role_name'];
    }
    ?>

    <img src="<?php echo $row['profile_img'] ?>" alt="logo" style="width: 100%; margin-top: 20px;">
    <h4 style="text-align: center;"><?php echo $row['last_name'] . " " . $row['first_name']; ?></h4>

    <a href="../order/orders.php" class="w3-bar-item w3-button">Orders</a>
    <?php if ($role > 1) { ?>
        <a href="../product/products.php" class="w3-bar-item w3-button">Products</a>
        <a href="../category/categories.php" class="w3-bar-item w3-button">Categories</a>
    <?php } ?>
    <?php if ($role > 2 || $role_name == 'Merchandize Manager') { ?>
        <a href="../tag/tags.php" class="w3-bar-item w3-button">Tags</a>
        <a href="../coupon/Coupons.php" class="w3-bar-item w3-button">Coupons</a>
        <a href="../sale/product_coupon.php" class="w3-bar-item w3-button">Sale</a>
    <?php } ?>
    <?php if ($role > 2 || $role_name == 'Staff Manager') { ?>

        <a href="../staff/staffs.php" class="w3-bar-item w3-button">Staffs</a>
        <a href="../role/roles.php" class="w3-bar-item w3-button">Roles</a>
    <?php } ?>
    <?php if ($role > 3 || $role_name == 'Admin') { ?>
        <a href="../customer/customers.php" class="w3-bar-item w3-button">Customers</a>
    <?php } ?>




</div>

<nav class="navbar navbar-expand-lg bg-body-tertiary" style="margin-left:200px">
    <div class="container-fluid">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="nofi_list()">
                        <i class="fa-solid fa-bell" style="color:black;"></i>
                        <span class="position-absolute top-70 start-75 translate-middle badge rounded-pill bg-danger">
                            <i id="nofi_number"></i>
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" id="addlist">

                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user" style="color:black;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../account/profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="../account/logout.php">Logout</a></li>
                    </ul>
                </li>

            </ul>

        </div>
</nav>
<div style="padding-left:200px">