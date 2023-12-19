<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");
include("includes/header.php");

if (isset($_GET) & !empty($_GET)) {
    $id = $_GET['product_id'];
} else {
    header("Location: cart.php");
}

if (isset($_POST) & !empty($_POST)) {
}

$sql = "SELECT * FROM customers_addresses WHERE customer_id=" . $_COOKIE['user'];
$result = mysqli_query($conn, $sql);
$customers_addresses = mysqli_fetch_all($result, MYSQLI_ASSOC);



?>
<div class="container section-padding">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h3>Address</h3>

            <div class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Phone Number</th>
                            <th>Address Line 1</th>
                            <th>Address Line 2</th>
                            <th>Ward</th>
                            <th>District</th>
                            <th>City</th>
                            <th>Default</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers_addresses as $customers_address) : ?>
                            <tr>
                                <td><?php echo $customers_address['phone_number']; ?></td>
                                <td><?php echo $customers_address['address_line1']; ?></td>
                                <td><?php echo $customers_address['address_line2']; ?></td>
                                <td><?php echo $customers_address['ward']; ?></td>
                                <td><?php echo $customers_address['district']; ?></td>
                                <td><?php echo $customers_address['city']; ?></td>
                                <td>
                                    <?php if ($customers_address['is_default'] == 1) : ?>
                                        <span class="badge bg-success">Yes</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="checkout.php?product_id=<?php echo $id; ?>&address_id=<?php echo $customers_address['id']; ?>" class="btn btn-primary">Use</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>