<?php
    //unset cookie
    setcookie("user", "", time() - 3600);
    setcookie("user_name", "", time() - 3600);
    header("Location: login.php")
?>