<?php
    if(!isset($_COOKIE['user'])){
        header("Location: login.php");
    }
    include("includes/db.php");

    $customer_id = $_COOKIE['user'];

    if(isset($_GET) & !empty($_GET)){
        $address_id = $_GET['address_id'];
    }
    else{
        header("Location: address.php");
    }
    
    if(isset($_POST) & !empty($_POST)){
        $address_id = $_POST['address_id'];
        $phone_number = $_POST['phone_number'];
        $address_line1 = $_POST['address_line1'];
        $address_line2 = $_POST['address_line2'];
        $ward = $_POST['ward'];
        $district = $_POST['district'];
        $city = $_POST['city'];

        $sql = "UPDATE customers_addresses SET address_line1='$address_line1', address_line2='$address_line2', ward='$ward', district='$district', city='$city', phone_number='$phone_number' WHERE customer_id='$customer_id' AND id='$address_id'";
        mysqli_query($conn, $sql);

        header("Location: address.php");
 
    }

    include("includes/header.php");
    include("includes/navbar.php");


    $sql = "SELECT * FROM customers_addresses WHERE customer_id='$customer_id' AND id='$address_id'";    
    $result = mysqli_query($conn, $sql);
    $customer_address = mysqli_fetch_assoc($result);
?>



<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Customer Address</h3>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="phone_number">Phone number</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number" value="<?php echo $customer_address['phone_number']; ?>" placeholder="Phone number">
                </div>
                <div class="form-group">
                    <label for="address_line1">Address line 1</label>
                    <input type="text" name="address_line1" class="form-control" id="address_line1" value="<?php echo $customer_address['address_line1']; ?>" placeholder="Address line 1">
                </div>
                <div class="form-group">
                    <label for="address_line2">Address line 2</label>
                    <input type="text" name="address_line2" class="form-control" id="address_line2" value="<?php echo $customer_address['address_line2']; ?>" placeholder="Address line 2">
                </div>
                <div class="form-group">
                    <label for="ward">Ward</label>
                    <input type="text" name="ward" class="form-control" id="ward" value="<?php echo $customer_address['ward']; ?>" placeholder="Ward">
                </div>
                <div class="form-group">
                    <label for="district">District</label>
                    <input type="text" name="district" class="form-control" id="district" value="<?php echo $customer_address['district']; ?>" placeholder="District">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" class="form-control" id="city" value="<?php echo $customer_address['city']; ?>" placeholder="City">
                </div>

                <input type="hidden" name="address_id" value="<?php echo $address_id; ?>" > 
                </br>
                <input type="submit" class="btn btn-primary float-end" value="Submit" >
            </form>
        </div>
    </div>
</div>

    

<?php
    include("includes/footer.php")
?>

