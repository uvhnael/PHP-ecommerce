<nav class="custom-navbar navbar navbar-expand-lg sticky-top bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">VL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars"
            aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <?php
      $curent_page = basename($_SERVER['PHP_SELF'], ".php");
      ?>
            <ul class="custom-navbar-nav navbar-nav me-auto mb-2 mb-lg-0">
                <li <?php if ($curent_page == "index") echo 'class="active"'; ?>>
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li <?php if ($curent_page == "products") echo 'class="active"'; ?>>
                    <a class="nav-link" href="products.php">Shop</a>
                </li>

            </ul>

            <form class="d-flex" role="search" action="products.php" method="get">
                <input class="form-control me-2" name="search_box" type="search" placeholder="Search"
                    aria-label="Search">
                <button class="btn btn-white-outline" type="submit">Search</button>
            </form>

            <ul class="custom-navbar-cta navbar-nav">
                <li class="nav-item">
                    <?php
          if (isset($_COOKIE['user'])) {
            $customer_id = $_COOKIE['user'];
            $sql = "SELECT * FROM carts WHERE customer_id = '$customer_id'";
            $result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);
          ?>
                    <a class="nav-link position-relative" href="cart.php">
                        <img src="img/cart.svg">
                        <span
                            class="position-absolute top-75 start-80 translate-middle badge rounded-pill bg-danger"><?php echo $count; ?><span
                                class="visually-hidden">unread messages</span></span>
                    </a>
                    <?php } ?>

                </li>
                <?php if (isset($_COOKIE['user'])) { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown"><img
                            src="img/user.svg"></a>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="order.php">Order</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <?php } ?>

        </div>
    </div>
</nav>