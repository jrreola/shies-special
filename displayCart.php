<?php

include_once "connection.php";

if(isset($_POST['price_id'])) {
    $user = $_SESSION['user_id'];
    $price_id = $_POST['price_id'];
    $item_id = $_POST['item_id'];
    $cat_id = $_POST['cat_id'];
    $size_id = $_POST['size_id'];
    $cart_qty = $_POST['item_qty'];

    $sql = "SELECT reservation_id, price_id, item_id, cat_id, size_id, item_quantity, order_status
            FROM reservation r
            WHERE `user_id` = ? 
              AND `price_id` = ?
              AND `item_id` = ?
              AND `cat_id` = ?
              AND `size_id` = ?
              AND `order_status` = 'C'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiiii", $user, $price_id, $item_id, $cat_id, $size_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if(mysqli_num_rows($result) == 0) {
        $order_qty = 0;
        $order_qty += $cart_qty;

        $table = "reservation";
        $fields = array( 'user_id' => $user
                        ,'price_id' => $price_id
                        ,'item_id' => $item_id
                        ,'cat_id' => $cat_id
                        ,'size_id' => $size_id
                        ,'item_quantity' => $order_qty
                        ,'order_status' => 'C'
                       );

        if(insert($conn, $table, $fields)) {
            header("location: menuPage.php?add_to_cart=success");
            exit();
        } else {
            header("location: menuPage.php?add_to_cart=failed");
            exit();
        } 
    } else {
        $reservation_id = $row['reservation_id'];
        $order_qty = $row['item_quantity'];
        $order_qty += $cart_qty;

        $table = "reservation";
        $fields = array('item_quantity' => $order_qty);
        $filter = array('reservation_id' => $reservation_id);

        if(update($conn, $table, $fields, $filter)) {
            header("location: menuPage.php?add_to_cart=success");
            exit();
        } else {
            header("location: menuPage.php?add_to_cart=failed");
            exit();
        }  
    }  
    mysqli_free_result($result);
    
    }
      

