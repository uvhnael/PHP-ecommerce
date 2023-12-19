<?php
include("includes/db.php");
if (isset($_POST['city_name']) & !empty($_POST['city_name'])) {
    $city_name = $_POST['city_name'];
    $sql = "SELECT districts.full_name FROM districts INNER JOIN provinces WHERE districts.province_code = provinces.code AND provinces.full_name='$city_name' ORDER BY districts.full_name DESC";
    $districts = mysqli_query($conn, $sql);
    foreach ($districts as $district) {
        echo "<option value='" . $district['full_name'] . "'>" . $district['full_name'] . "</option>";
    }
}
if (isset($_POST['district_name']) & !empty($_POST['district_name'])) {
    $district_name = $_POST['district_name'];
    $sql = "SELECT wards.full_name FROM wards INNER JOIN districts WHERE wards.district_code = districts.code AND districts.full_name='$district_name' ORDER BY wards.full_name DESC";
    $wards = mysqli_query($conn, $sql);
    foreach ($wards as $ward) {
        echo "<option value='" . $ward['full_name'] . "'>" . $ward['full_name'] . "</option>";
    }
}
