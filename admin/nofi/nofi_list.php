<?php
                include_once('../includes/db.php');
                session_start();
                $id = $_SESSION['id'];
                $sql = "SELECT * FROM notifications WHERE account_id='$id' AND seen=0 ORDER BY created_at DESC LIMIT 10";
                $result = mysqli_query($conn, $sql);
                $notifications = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $notifications_count = mysqli_num_rows($result);

              

                if($notifications_count == 0){
                  echo "<li><a class='dropdown-item'>No new notifications</a></li>";
                }
                else
                {
                  
                  echo "<li><a class='dropdown-item text-center'>Notifications</a></li>";
                  if($notifications_count <9) {
                    $limit = 9-$notifications_count;
                    $sql = "SELECT * FROM notifications WHERE account_id='$id' AND seen=1 ORDER BY created_at DESC LIMIT $limit";
                    $result = mysqli_query($conn, $sql);
                    // add to notifications
                    $notifications = array_merge($notifications, mysqli_fetch_all($result, MYSQLI_ASSOC));
                  }
                  foreach($notifications as $notification){
                    // split order_id from content affter #
                    $order_id = explode("#", $notification['tittle']);
              ?>
<li>
    <hr class="dropdown-divider">
</li>
<li>
    <div style="background:<?php if($notification['seen'] == 0) echo "#f1f1f1"; else echo "#ffffff";  ?>">

        <a class="dropdown-item"
            href="../nofi/readed.php?nofi_id=<?php echo $notification['id']."&order_id=".$order_id[1];?>">
            <b><?php echo $notification['tittle']; ?></b>
            <br />
            <?php echo $notification['content']; ?>
        </a>
    </div>
</li>
<?php 
                  } 
                }?>