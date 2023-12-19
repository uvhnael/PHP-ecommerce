<?php

if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}

include("includes/db.php");


if (isset($_GET['search_box'])) {
    $query = mysqli_real_escape_string($conn, $_GET['search_box']);
    $sql = "SELECT * FROM products WHERE product_name LIKE '%$query%' OR short_description LIKE '%$query%' ";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {

    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$row = mysqli_num_rows($result);

if (($row / 12) > round($row / 12))
    $page = round($row / 12) + 1;
else
    $page = round($row / 12);




include("includes/header.php");
include("includes/navbar.php");

?>

<div class="product-section">
    <div class="container">
        <div class="row">
            <?php
            // 12 items per page
            if (isset($_GET['page'])) {
                $curent_page = $_GET['page'];
            } else {
                $curent_page = 1;
            }


            $start = ($curent_page - 1) * 12;
            $end = $start + 12;
            for ($i = $start; $i < $end; $i++) {
                if ($i >= $row)
                    break;
                $product = $products[$i];

            ?>
                <div class="col-12 col-md-4 col-lg-3 mb-5">
                    <a class="product-item" href="product.php?id=<?php echo $product['id']; ?>">
                        <?php
                        $sql = "SELECT * FROM galleries WHERE product_id={$product['id']} AND thumbnail=1";
                        $result = mysqli_query($conn, $sql);
                        $product_image = mysqli_fetch_assoc($result);
                        ?>
                        <img src="<?php echo $product_image['image_path']; ?>" class="img-fluid product-thumbnail">
                        <h3 class="product-title"><?php echo $product['product_name']; ?></h3>
                        <strong class="product-price"><span>&#8363;</span><?php echo $product['regular_price']; ?></strong>

                        <span class="icon-cross">
                            <img src="img/cross.svg" class="img-fluid">
                        </span>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-4 col-lg-3 mb-5">
                <?php
                if ($page != 1) {
                    if (isset($_GET['page'])) {
                        $curent_page = $_GET['page'];
                    } else {
                        $curent_page = 1;
                    }
                    $page_before = max(1, $curent_page - 2);
                    $page_after = min($page_before + 4, $page);
                    if ($curent_page != 1) {
                        if (isset($_GET['search_box'])) {
                ?>
                            <a class="mx-1 a-black" href="products.php?page=<?php echo $curent_page - 1; ?>&search_box=<?php echo $_GET['search_box']; ?>"><i class="fa-solid fa-angle-left fa-2xl"></i></a>

                        <?php
                        } else { ?>
                            <a class="mx-1 a-black" href="products.php?page=<?php echo $curent_page - 1; ?>"><i class="fa-solid fa-angle-left fa-2xl"></i></a>
                        <?php
                        }
                    }
                    for ($i = $page_before; $i <= $page_after; $i++) {
                        if (isset($_GET['search_box'])) {
                        ?>
                            <a class="btn btn-outline-dark mx-1" href="products.php?page=<?php echo $i; ?>&search_box=<?php echo $_GET['search_box']; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php
                        } else { ?>
                            <a class="btn btn-outline-dark mx-1" href="products.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php
                        }
                    }
                    if ($curent_page != $page) {
                        if (isset($_GET['search_box'])) {
                        ?>
                            <a class="mx-1 a-black" href="products.php?page=<?php echo $curent_page + 1; ?>&search_box=<?php echo $_GET['search_box']; ?>"><i class="fa-solid fa-angle-right fa-2xl"></i></a>

                        <?php
                        } else { ?>
                            <a class="mx-1 a-black" href="products.php?page=<?php echo $curent_page + 1; ?>"><i class="fa-solid fa-angle-right fa-2xl"></i></i></a>
                <?php
                        }
                    }
                }
                ?>
            </div>

        </div>
    </div>
</div>




<?php
include("includes/footer.php");
?>