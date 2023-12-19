<?php
session_start();
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 2) {
        header('location: ../index.php');
    }
}
require_once('../includes/db.php');
$product_id = $_GET['product_id'];
$sql = "SELECT * FROM galleries WHERE product_id='$product_id'";
$result = mysqli_query($conn, $sql);
$galleries = mysqli_fetch_all($result, MYSQLI_ASSOC);
foreach ($galleries as $gallery) {

    $imgSrc = $gallery['image_path'];

    // Getting the image dimensions
    list($width, $height) = getimagesize($imgSrc);

    if ($width !== $height) {

        $extension = pathinfo($imgSrc, PATHINFO_EXTENSION);
        // Saving the image into memory (for manipulation with GD Library)
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $myImage = imagecreatefromjpeg($imgSrc);
        } else if ($extension == 'png') {
            $myImage = imagecreatefrompng($imgSrc);
        }

        // Calculating the part of the image to use for thumbnail
        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
            $thumbSize = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
            $thumbSize = $width;
        }

        // crop image into a square
        $thumb = imagecreatetruecolor($thumbSize, $thumbSize);
        imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);



        // save thumbnail into a file

        imagejpeg($thumb, $imgSrc);

        // release the memory
        imagedestroy($myImage);
        imagedestroy($thumb);
    }
}
if (isset($_GET['goto'])) {
    header("Location: " . $_GET['goto'] . ".php?product_id=$product_id");
}
header("Location: add_variant.php?product_id=$product_id");
