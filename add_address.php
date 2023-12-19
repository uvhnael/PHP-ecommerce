<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");
include("includes/header.php");
include("includes/navbar.php");

if (isset($_POST) & !empty($_POST)) {
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $ward = $_POST['ward'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $phone_number = $_POST['phone_number'];
    $customer_id = $_COOKIE['user'];


    $sql = "INSERT INTO customers_addresses (customer_id, phone_number, address_line1, address_line2, ward, district, city) VALUES ('$customer_id', '$phone_number', '$address_line1', '$address_line2', '$ward', '$district', '$city')";
    mysqli_query($conn, $sql);

    // check if this is the first address of the customer
    $sql = "SELECT * FROM customers_addresses WHERE customer_id='$customer_id'";
    $result = mysqli_query($conn, $sql);
    $customers_addresses = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if (count($customers_addresses) == 1) {
        $sql = "UPDATE customers_addresses SET is_default=1 WHERE id=" . $customers_addresses[0]['id'];
        mysqli_query($conn, $sql);
    }
    header("Location: address.php");
}


?>

<div class="container section-padding">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <h3>Add new addres</h3>
            <form method="post">
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number"
                        placeholder="Phone Number" />
                </div>
                <div class="form-group">
                    <label for="address_line1">Address Line 1</label>
                    <input type="text" name="address_line1" class="form-control" id="address_line1"
                        placeholder="Address Line 1" />
                </div>
                <div class="form-group">
                    <label for="address_line2">Address Line 2</label>
                    <input type="text" name="address_line2" class="form-control" id="address_line2"
                        placeholder="Address Line 2" />
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <select name="city" class="form-control" id="city">
                        <?php
                        $sql = "SELECT * FROM provinces";
                        $provinces = mysqli_query($conn, $sql);
                        foreach ($provinces as $province) {
                            echo "<option value='" . $province['full_name'] . "'>" . $province['full_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="district">District</label>
                    <select name="district" class="form-control" id="district">
                    </select>
                </div>
                <div class="form-group">
                    <label for="ward">Ward</label>
                    <select name="ward" class="form-control" id="ward">
                    </select>
                </div>
                </br>
                <div class="d-grid gap-2 col-12 mx-auto">
                    <button type="submit" class="btn btn-primary ">Add address</button>
                </div>




            </form>
        </div>
    </div>
</div>

<script>
// add event listener
const cityField = document.getElementById("city");
const districtField = document.getElementById("district");

cityField.addEventListener("change", function() {
    const city = cityField.value;
    get_districts(city);

});
districtField.addEventListener("change", function() {
    const district = districtField.value;
    get_wards(district);
});

function get_districts(city) {
    $.ajax({
        url: "get_address.php",
        type: "POST",
        data: {
            city_name: city
        },
        success: function(data) {
            $("#district").html(data);
        }
    });
}

function get_wards(district) {
    $.ajax({
        url: "get_address.php",
        type: "POST",
        data: {
            district_name: district
        },
        success: function(data) {
            $("#ward").html(data);
        }
    });
}


city.add
</script>

<?php
include("includes/footer.php");
?>