<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");

$customer_id = $_COOKIE['user'];

include("includes/header.php");
include("includes/navbar.php");

?>
<div class="container section-padding">
    <div class="row justify-content-md-center">
        <?php include("includes/sidebar.php"); ?>
        <div class="col-md-6">
            <div class="container">
                <h3>Profile</h3>
                <?php
                // print all customer's addresses
                $sql = "SELECT * FROM customers_addresses WHERE customer_id='$customer_id'";
                $result = mysqli_query($conn, $sql);
                $customers_addresses = mysqli_fetch_all($result, MYSQLI_ASSOC);
                foreach ($customers_addresses as $customers_address) {


                ?>
                <hr style="height:2px;border-width:0;color:gray;background-color:gray">
                <div class="row container-padding">
                    <div class="col-md-10" style="width:470px">

                        <div class="fw-bold text-break">
                            <?php $sql = "SELECT * FROM customers WHERE id=" . $customers_address['customer_id'];
                                $result = mysqli_query($conn, $sql);
                                $customer = mysqli_fetch_assoc($result);
                                echo $customer['last_name'] . " " . $customer['first_name'] . " - " . $customers_address['phone_number'] . " - " . $customer['email'];
                                ?>
                        </div>
                        <?php
                            echo $customers_address['address_line1'] . ", " . $customers_address['address_line2'] . ",</br> " . $customers_address['ward'] . ", " . $customers_address['district'] . ", " . $customers_address['city'];
                            ?>
                    </div>

                    <div class="d-grid col-1 justify-content-md-end">
                        <a href="edit_address.php?address_id=<?php echo $customers_address['id']; ?>"
                            class="btn btn-primary ">Edit</a>
                        <a href="delete_address.php?address_id=<?php echo $customers_address['id']; ?>"
                            class="btn btn-danger">Delete</a>
                        <?php 
                        if($customers_address['is_default'] == 0)  {
                            ?>
                        <a href="set_default_address.php?address_id=<?php echo $customers_address['id']; ?>"
                            class="btn btn-success ">Set Default</a>
                        <?php } ?>

                    </div>

                    <?php
                }
                ?>
                </div>
                <hr style="height:2px;border-width:0;color:gray;background-color:gray">
                <div class="d-grid gap-2 col-4 mx-auto">
                    <a href="add_address.php" class="btn btn-primary">Add new address</a>
                </div>
            </div>
        </div>
    </div>

    <?
include("includes/footer.php");
?>