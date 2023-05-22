<?php
include_once "connection.php";

if(isset($_POST['email_add'])){
    $n_fname=$_POST['fname'];
    $n_lname=$_POST['lname'];
    $n_email_add=$_POST['email_add'];
    $n_password=$_POST['password'];
    $p_address = $_POST['address'];
    $p_contact_num = $_POST['contact_num'];
    $p_user_type = $_POST['user_type'];
    
    $table = "user";
    $fields = array( 'fname' => $n_fname
                    , 'lname' => $n_lname  
                    , 'email_add' => $n_email_add  
                    , 'password' => $n_password
                    , 'address' => $p_address
                    , 'contact_num' => $p_contact_num
                    , 'user_type' => $p_user_type 
                   );
    
    if(insert($conn, $table, $fields) ){
        header("location: index.php?newuser&new_record=added");
        exit();
    }  
    else{
        header("location: new_user.php?newuser&new_record=failed");
        exit();
    }
}
?>