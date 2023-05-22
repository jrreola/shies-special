<?php
/**
 * Check if a value exists in a column of a table
 *
 * @param mysqli $conn The database connection object
 * @param string $value The value to check
 * @param string $column The column name to check
 * @param string $table The table name to check
 * @return bool Returns true if the value exists in the column, false otherwise
 */

function is_existing(mysqli $conn, string $value, string $column, string $table): bool
{
    $value = mysqli_real_escape_string($conn, $value);
    $column = mysqli_real_escape_string($conn, $column);
    $table = mysqli_real_escape_string($conn, $table);

    $query = "SELECT COUNT(*) AS count FROM $table WHERE $column = '$value'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return ($row['count'] > 0);
    }

    return false;
}

function count_cart_items($conn, $user) {
    $sql = "SELECT COUNT(ORDER_ID) as cart FROM orders WHERE order_status='X' and user_id = ? ";
    $res = query($conn, $sql, array($user));
    foreach($res as $r){
        return $r['cart'];
    }
}

function get_items_from_order($conn, $reservation_id){
    return query($conn, "SELECT i.item_id, r.item_quantity, pr.item_price
                            FROM reservation r
                            INNER JOIN item i ON r.item_id = i.item_id
                            LEFT JOIN (
                                SELECT item_id, MAX(price_id) AS price_id
                                FROM price
                                WHERE (CURRENT_DATE BETWEEN start_date AND end_date) OR (start_date IS NULL)
                                GROUP BY item_id
                            ) AS prmax ON i.item_id = prmax.item_id
                            JOIN price pr ON prmax.item_id = pr.item_id AND prmax.price_id = pr.price_id
                            WHERE reservation_id = ?
                            AND order_status = ' ' ", array($reservation_id));
}

function admin_retrieve_orders($conn, $sql_1,$sql_2, $status ='P', $mode = 'V'){
    //mode = V = view or E = edit or C = count_order_reference
       if($mode == 'C'){
           return count(query($conn,$sql_1,array($status)));
       }
    else if($mode == 'V'){
     echo "<table class='table table-responsive table-striped table-borderless'>";               
      $f_orders=query($conn, $sql_1, array($status));
        if(count($f_orders) > 0){
            foreach($f_orders as $ord){ ?>
              <tr class='border border-1' data-bs-toggle="collapse" href="#<?php echo $ord['order_ref_number']; ?>" role="button" aria-expanded="false" aria-controls="<?php echo $ord['order_ref_number'];?>">
              <?php 
                                       echo "<td><b>" . $ord['order_ref_number'] . "<b></td>" ;
                                       echo "<td>" . $ord['date_ordered'] . "</td>" ;
                                       echo "<td>" . $ord['order_count'] . "</td>" ; 
                                       $total_amt=0;
                                      $order_ref_number =  $ord['order_ref_number'];
                                      $show_order_item = query($conn, $sql_2, array($status, $order_ref_number));
                                       foreach($show_order_item as $idet){
                                            $total_amt += $idet['item_price'] * $idet['item_qty'];
                                       }
                                       define('CURRENCY', 'Php '); 
                                       echo "<td>" . CURRENCY . number_format($total_amt,2) . "</td>" ;  ?>
                                       <td><?php echo strtoupper($ord['fname']) . ", (". $ord['contact_number'] .")"; ?></td>
              </tr>
              <?php 
            
            //echo "<div id=". $ord['order_ref_number'] . " class='collapse'>";
              foreach($show_order_item as $idet){
                  $total_amt += $idet['item_price'] * $idet['item_qty']; ?>
              <tr class="collapse" id="<?php echo $ord['order_ref_number'];?>">
              <?php
                 echo "<td class='float-end'>" . $idet['item_name'] ."</td>";
                 echo "<td class='float-end'>" . $idet['item_price'] . " x " . $idet['item_qty'] ."</td>";
                 echo "<td>" . number_format($idet['item_price'] * $idet['item_qty'],2) ."</td>"; ?>
              </tr>
              <?php }
             // echo "<tr><td colspan='2'>Total Amount</td><td><i class='text-danger'>Php" . number_format($total_amt,2) . "</i></td></tr>";

            //echo "</div>";          
                }
       }
      else{
            echo "<tr><td>No Orders.</td></tr>";
      }
     echo "</table>";
    }
    else if($mode == 'E'){
        echo "<table class='table table-responsive  table-borderless text-light'>";     
      $f_orders=query($conn, $sql_1, array($status));
        if(count($f_orders) > 0){
            
            echo "<tr>";
                echo "<td></td>";
                echo "<td>Reference No.</td>";
                echo "<td>Total Amount</td>";
                echo "<td>Date Ordered</td>";
                echo "<td>Customer Name</td>";
            echo "</tr>";
            foreach($f_orders as $ord){ ?>
              <tr>
                 <td>
                   <?php 
                     switch($status){ 
                         case 'P':
                           ?>
                             <a href="process_orders.php?conf_ord=<?php echo $ord['order_ref_number']; ?>" class="btn btn-primary z-1"> Confirm Order</a>
                            <?php break; ?>
                  <?php  case 'O': ?>
                              <a href="process_orders.php?update_delivery=<?php echo $ord['order_ref_number']; ?>&del_status=D" class="btn btn-primary z-1"> Delivered</a>
                              <a href="process_orders.php?update_delivery=<?php echo $ord['order_ref_number']; ?>&del_status=R" class="btn btn-danger z-1"> Rejected</a>
                            <?php break; ?>
                     <?php }
                     ?>
                    
                 </td>
                 <td><a data-bs-toggle="collapse" href="#<?php echo $ord['order_ref_number']; ?>" role="button" aria-expanded="false" aria-controls="<?php echo $ord['order_ref_number'];?>" ><?php echo $ord['order_ref_number']; ?></a></td>
                 <td style='text-align:right'>
                     <?php 
                        $order_ref_number=$ord['order_ref_number'];
                        $show_order_item = query($conn, $sql_2, array($status, $order_ref_number));
                        $total_amt=0;
                        foreach($show_order_item as $i){
                           $total_amt += $i['item_price'] * $i['item_quantity'];
                        }  
                     echo number_format($total_amt,2);
                     ?>
                 </td>
                  <td><?php echo $ord['date_ordered']; ?></td>
                  <td><?php echo strtoupper($ord['fname']) . ", " . ($ord['lname']); ?></td>
              </tr>
              <?php 
             
            //echo "<div id=". $ord['order_ref_number'] . " class='collapse'>";
              foreach($show_order_item as $idet){
                 ?>
              <tr class="collapse fade align-items-center" id="<?php echo $ord['order_ref_number'];?>">
                  <td><img style="width:100px" src="../img/<?php echo $idet['item_file'];?>" alt="" class="img-thumbnail"></td>
              <?php
                 echo "<td style='text-align:right'>" . $idet['item_name'] ."</td>";
                 echo "<td>" . $idet['item_price'] . " x " . $idet['item_quantity'] ."</td>";
                 echo "<td style='text-align:right'>" . number_format($idet['item_price'] * $idet['item_quantity'],2) ."</td>"; ?>
              </tr>
              <?php }
             // echo "<tr><td colspan='2'>Total Amount</td><td><i class='text-danger'>Php" . number_format($total_amt,2) . "</i></td></tr>";

            //echo "</div>";          
                }
       }
      else{
            echo "<tr><td class=text-light></td></tr>";
            echo "<tr><td>No Orders.</td></tr>";
      }
     echo "</table>";
    }
}

//this is to check if the user is logged. if not, it will be redirected to specific $location.
//@param $usertype = array('A','D')
function session_check($usertype, $loc){
    if(isset($_SESSION['user']['user_type'] )){
        if(!in_array($_SESSION['user']['user_type'], $usertype) ){
           header("location: $loc ");
        //   exit();
        }
    }
    else{
          header("location: $loc");
          // exit();
    }
}



function encrypt_password($password, $salt ) {
    $hash = hash('sha256', $password . $salt);
    return $hash;
}
function verify_password($password, $hash, $salt) {
 
    $hash_to_verify = hash('sha256', $password . $salt);
    return $hash_to_verify === $hash;
}
//This function takes in a password and a hash (which would be retrieved from a database or other storage), adds the same salt string as the encryption function, and generates a hash using the SHA256 algorithm. It then compares this hash to the original hash, and returns true if they match, indicating that the password is correct.

function gen_private_key($len){
    $alpha_num=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','0');
    $key="";
    for ($i = 0; $i <= $len; $i++){
        if($i%2 == 0 && $i > 0){
           $key .= $alpha_num[rand(0,52)];
        }
        else{
             $key .= $alpha_num[rand(53,62)];
        }
     }
    return $key;
}
function gen_order_ref_number($len){
    $alpha_num=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9','0');
    $key="";
    for ($i = 0; $i <= $len; $i++){
        if($i%2 == 0 && $i > 0){
           $key .= $alpha_num[rand(0,25)];
        }
        else{
             $key .= $alpha_num[rand(26,sizeof($alpha_num)-1)];
        }
     }
    return $key;
}


function getSalesReportByDay($conn, $date) {
    
    // Perform the SQL query to retrieve items sold on the given day
    $query = "SELECT i.item_name, r.pickup_date, SUM(r.item_quantity) as total_quantity_sold, SUM(r.item_quantity * pr.item_price) as total_sales
                FROM reservation r
                JOIN item i ON r.item_id = i.item_id
                JOIN price pr ON r.price_id = pr.price_id
                WHERE r.order_status = 'R' AND DATE(r.pickup_date)
                GROUP BY i.item_name, r.pickup_date";
    $result = mysqli_query($conn, $query);

    // Perform the SQL query to retrieve the overall total sales for the given day
    $query2 = "SELECT SUM(r.item_quantity * pr.item_price) as overall_total_sales
                FROM reservation r
                JOIN item i ON r.item_id = i.item_id
                JOIN price pr ON r.price_id = pr.price_id
                WHERE r.order_status = 'R' AND DATE(r.pickup_date)";
               
    $result2 = mysqli_query($conn, $query2);
    $row2 = mysqli_fetch_assoc($result2);
    $overall_total_sales = $row2['overall_total_sales'];

    // Display the result in a table format
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<div class='col-lg-8 mx-auto my-3'>"; 
        echo "<div class='row justify-content-end'>"; 
        echo "<div class='col-12 col-lg-9 mx-auto my-3'>"; 
        echo "<table class='table table-bordered bg-transparent text-light'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Item Name</th>";
        echo "<th>Pickup Date</th>";
        echo "<th>Total Quantity Sold</th>";
        echo "<th>Total Sales</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['item_name'] . "</td>";
            echo "<td>" . $row['pickup_date'] . "</td>";
            echo "<td>" . (isset($row['total_quantity_sold']) ? $row['total_quantity_sold'] : '') . "</td>";
            echo "<td>" . (isset($row['total_sales']) ? $row['total_sales'] : '') . "</td>";
            echo "</tr>";
        }
        echo "<tr>";
        echo "<td colspan='3'><strong>Overall Total Sales:</strong></td>";
        echo "<td><strong>" . $overall_total_sales . "</strong></td>";
        echo "</tr>";
        echo "</tbody>";
        echo "</table>";
        echo "</div>"; 
        echo "</div>"; 
        echo "</div>";
    } else {
        // If there is no data, display a message
        echo "<p style='text-align:center; color:white;'>No sales data found for this date range.</p>";
    }
         
    }
    function getSalesReportByRange($conn, $start_date, $end_date) {
        // Perform the SQL query to retrieve items sold within the given date range
        $query = "SELECT i.item_name, r.pickup_date, SUM(r.item_quantity) as total_quantity_sold, SUM(r.item_quantity * pr.item_price) as total_sales
                    FROM reservation r
                    JOIN item i ON r.item_id = i.item_id
                    JOIN price pr ON r.price_id = pr.price_id
                    WHERE r.order_status = 'D' AND DATE(r.pickup_date)>= '$start_date' AND DATE(r.pickup_date)<= '$end_date'
                    GROUP BY i.item_name, r.pickup_date";
        $result = mysqli_query($conn, $query);
    
        // Perform the SQL query to retrieve the overall total sales for the given date range
        $query2 = "SELECT SUM(r.item_quantity * pr.item_price) as overall_total_sales
                    FROM reservation r
                    JOIN item i ON r.item_id = i.item_id
                    JOIN price pr ON r.price_id = pr.price_id
                    WHERE r.order_status = 'D' AND DATE(r.pickup_date) >= '$start_date' AND DATE(r.pickup_date) <= '$end_date'";
        $row2 = mysqli_fetch_assoc($result2);
        $overall_total_sales = $row2['overall_total_sales'];
    
        // Display the result in a table format
        $result2 = mysqli_query($conn, $query2);
        $row2 = mysqli_fetch_assoc($result2);
        $overall_total_sales = $row2['overall_total_sales'];
    
        // Display the result in a table format
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<div class='col-lg-8 mx-auto my-3'>"; 
            echo "<div class='row justify-content-end'>"; 
            echo "<div class='col-12 col-lg-9 mx-auto my-3'>"; 
            echo "<table class='table table-bordered bg-transparent text-light'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Item Name</th>";
            echo "<th>Pickup Date</th>";
            echo "<th>Total Quantity Sold</th>";
            echo "<th>Total Sales</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['item_name'] . "</td>";
                echo "<td>" . $row['pickup_date'] . "</td>";
                echo "<td>" . (isset($row['total_quantity_sold']) ? $row['total_quantity_sold'] : '') . "</td>";
                echo "<td>" . (isset($row['total_sales']) ? $row['total_sales'] : '') . "</td>";
                echo "</tr>";
            }
            echo "<tr>";
            echo "<td colspan='3'><strong>Overall Total Sales:</strong></td>";
            echo "<td><strong>" . $overall_total_sales . "</strong></td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>"; 
            echo "</div>"; 
            echo "</div>";
        } else {
            // If there is no data, display a message
            echo "<p style='text-align:center; color:white;'>No sales data found for this date range.</p>";
        }
             
        }
    ?>
    
