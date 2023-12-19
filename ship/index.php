<?php
    include("../includes/db.php");
    include("includes/header.php");
    if(isset($_GET['status']) & !empty($_GET['status'])){
        $status = $_GET['status'];
        if($status == 1)
        {
            $sql = "SELECT * FROM orders WHERE order_delivered_carrier_date = '0000-00-00 00:00:00' AND order_status_id =2 ORDER BY id DESC";
        }
        else if($status == 2)
        {
            $sql = "SELECT * FROM orders WHERE order_delivered_customer_date = '0000-00-00 00:00:00'AND order_delivered_carrier_date != '0000-00-00 00:00:00'  AND order_status_id =2 ORDER BY id DESC";
        }
        else 
        {
            $sql = "SELECT * FROM orders WHERE order_delivered_customer_date != '0000-00-00 00:00:00' AND order_status_id =3 ORDER BY id DESC";
        }
    }
    else{
        $sql = "SELECT * FROM orders WHERE (order_delivered_carrier_date = '0000-00-00 00:00:00' OR order_delivered_customer_date = '0000-00-00 00:00:00') AND order_status_id = 2 ORDER BY id DESC";

    }
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
    <h2>Orders list</h2>

    <div class="btn-group" role="group" aria-label="Basic example">
        <a href="index.php" class="btn btn-secondary">All Orders</a>
        <a href="index.php?status=1" class="btn btn-secondary">Pending</a>
        <a href="index.php?status=2" class="btn btn-secondary">Shipping</a>
        <a href="index.php?status=3" class="btn btn-secondary">Completed</a>
    </div>


    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Order ID</th>
                <th scope="col">Customer</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
                <th scope="col">Price</th>
                <th scope="col" colspan="2">Action</th>
            </tr>
        </thead>
        <tbody> 
        <?php foreach($orders as $order){ ?>
            <tr>
                <td scope="row"><?php echo $order['id']; ?></td>
                <td>
                    <?php
                        $sql ="SELECT * FROM customers WHERE id={$order['customer_id']}";
                        $result = mysqli_query($conn, $sql);
                        $customer = mysqli_fetch_assoc($result);
                        echo $customer['last_name'] . " " . $customer['first_name']; 
                    ?>
                </td>
                <td><?php echo $order['created_at']; ?></td>
                <?php
                    $sql ="SELECT SUM(quantity * price) AS total_price FROM order_items WHERE order_id={$order['id']}";
                    $result = mysqli_query($conn, $sql);
                    $order_total_price = mysqli_fetch_assoc($result);
                ?>
                 <td>
                    <?php
                        $sql ="SELECT * FROM order_statuses WHERE id={$order['order_status_id']}";
                        $result = mysqli_query($conn, $sql);
                        $order_status = mysqli_fetch_assoc($result);
                    ?>
                    <a style="color: <?php echo $order_status['color'] ?>"><i class="fa-solid fa-circle fa-xs"></i> <?php echo $order_status['status_name']?></a>
                </td>
                <td><?php echo "₫".$order_total_price['total_price']; ?></td>
               
                <td>
                    <?php
                        if($order['order_delivered_carrier_date'] == '0000-00-00 00:00:00'){
                        ?>
                        <a class="btn btn-success"  href="update_ship.php?id=<?php echo $order['id']; ?>&status=2">Accept</a>
                        <?php
                        }
                        else if($order['order_delivered_customer_date'] == '0000-00-00 00:00:00'){
                        ?>
                        <a class="btn btn-success" href="update_ship.php?id=<?php echo $order['id']; ?>&status=3">Complete</a>
                        
                        <?php
                        }
                    ?>
                </td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php
    include("includes/footer.php");
?>
