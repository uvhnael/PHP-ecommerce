<?php
include("includes/db.php");
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
include("includes/header.php");
include("includes/navbar.php");


?>
<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php for ($i = 1; $i < 5; $i++) { ?>
        <div class="carousel-item <?php if ($i == 1) {
                                            echo "active";
                                        } ?>">

            <img src="<?php echo "img/slideshow" . $i . ".png"; ?>" class="d-block w-100">
        </div>
        <?php } ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Trước</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Sau</span>
    </button>
</div>
<?php
include("includes/footer.php");
?>