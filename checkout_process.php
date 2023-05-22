<?php
include('connection.php');

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM user WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        echo "Error: User not found.";
        exit();
    }

if(isset($_POST['pickup_date'])) {
    date_default_timezone_set("Asia/Manila");
    $pickup_date = $_POST['pickup_date'];
    
    $sql = "SELECT order_ref_number, date_ordered, pickup_date, order_status
            FROM reservation
            WHERE user_id = $user_id";
    $result = query($conn, $sql);
    
    if(!empty($result)) {
        $ref_number = gen_order_ref_number(12);
        
        foreach($result as $key => $row) {
            
            $table = "reservation";
            $fields = array( 'order_ref_number' => $ref_number
                           , 'date_ordered' => date("Y-m-d H:i:s")
                           , 'pickup_date' => $pickup_date
                           , 'order_status' => 'P'
                           );
            $filter = array( 'order_status' => 'C' );
            
            update($conn, $table, $fields, $filter);
        }
        
        if(update($conn, $table, $fields, $filter)) {
            header("location: order_list.php?checkout=success");
            exit;
        } else {
            header("location: order_list.php?checkout=failed");
            exit;
        }
    }
}
?>
