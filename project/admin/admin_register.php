<?php 
include('../config.php');
session_start();
$admin_id =  $_SESSION['admin_id'];

if(!isset($admin_id)){
header('location:admin_login.php');
}
if(isset($_POST['register'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
 
    $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
    $select_admin->execute([$name]);
 
    if($select_admin->rowCount() > 0){
       $error[] = 'Username already exist!';
    }else{
       if($pass != $cpass){
          $error[] = 'Password not matched!';
       }else{
          $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
          $insert_admin->execute([$name, $cpass]);
          $error[] = 'New admin registered successfully!';
       }
    }
 
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>New Admin Register</title>
 
<link rel="stylesheet" href="../css/admin.css">
   <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
  
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">

<body>
    <?php 
    include('../partials/admin_header.php');
    ?>
<section class="admin-login">

   <h3 class="heading">Register New Admin</h3> 
<form action="" method="post">
<?php 
            if(isset($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                };
            };
            ?>
       <input type="text" name="name" required placeholder="Enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" class="btn" name="register">
</form>

</section>
    <script src="../index.js"></script>
    <script src="../admin/admin.js"></script>
</body>
</html>