<!DOCTYPE html>
<html>

<head>
    <title>Users</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <!-- Link to Bootstrap CSS -->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
</head>

<body class="bg-transparent text-light">
    <div class="container-fluid bg-transparent text-light">   
        <div class="row">
            <div class="col-7 mx-auto my-3">
                <h3 class="display text-center mt-3 mb-3">
                    CREATE USER ACCOUNT
                </h3>
    <div class="container">
        <div class="row">
        <div class="col-2"></div>
        <div class="col-8 bg-transparent mb-2" style="color: light; text-align: center;">
                <h3 class="mt-3">New Record</h3>
                <?php
                     if(isset($_GET['new_record'])){
                            switch($_GET['new_record']){
                                case "added": echo "<div class='alert alert-success'>User Added.</div>";
                                      break;
                                case "failed":  echo "<div class='alert alert-danger'>User Not Added</div>";
                                      break;
                                        
                            }
                       }
                ?>
                <form action="new_user_record.php" method="post">
                    <div class="mb-3 mt-3">
                        <input type="text" required id="new_fname" placeholder="Firstname" name="fname" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="text" required id="new_lname" placeholder="Lastname" name="lname" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="text" id="email_add" required placeholder="Email" name="email_add" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="password" required id="new_password" placeholder="Password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="text" required id="new_address" placeholder="Address" name="address" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="number" required id="new_contact_num" placeholder="Contact Number" name="contact_num" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>C = Customer , A = Admin</label>
                        <input type="text" required id="user_type" placeholder="User Type" name="user_type" class="form-control">
                    </div>
                        <input type="submit" class="btn btn-primary float-end mt-3 mb-3">
                </form>
            </div>
            <div class="col-2"></div>
            
        </div>
    </div>

    <!-- Link to jQuery -->
<!--    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>-->
    <!-- Link to Bootstrap JS -->
<!--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->

</body>

<script>
    var userTypeInput = document.getElementById("user_type");
    userTypeInput.addEventListener("input", function() {
        this.value = this.value.toUpperCase();
    });
</script>

</html>