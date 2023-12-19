<div class="col-md-2">
    <div>
        <div style="margin-top: 40px"></div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">

                <a class="text-decoration-none" href="profile.php">My Account</a>
                <?php
                $current_page = basename($_SERVER['PHP_SELF'], ".php");
                $page = array("profile", "change_password", "address");
                if (in_array($current_page, $page)) {
                ?>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item "><a class="text-decoration-none" href="profile.php">Profile</a></li>
                        <li class="list-group-item"><a class="text-decoration-none" href="change_password.php">
                                Password</a></li>
                        <li class="list-group-item"><a class="text-decoration-none" href="address.php">Address</a></li>
                    </ul>
                <?php
                }
                ?>

            </li>
            <li class="list-group-item"><a style="text-decoration: none;" href="order.php">Order</a></li>
        </ul>
    </div>
</div>