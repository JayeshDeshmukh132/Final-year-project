<?php 
include('../config.php');
session_start();
$admin_id =  $_SESSION['admin_id'];

if(!isset($admin_id)){
header('location:admin_login.php');
}
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $update_profile_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
    $update_profile_name->execute([$name, $admin_id]);

    $prev_pass=$_POST['prev_pass'];
    $old_pass=sha1($_POST['old_pass']);
    $old_pass=filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass=sha1($_POST['new_pass']);
    $new_pass=filter_var($new_pass, FILTER_SANITIZE_STRING);
    $confirm_pass=sha1($_POST['confirm_pass']);
    $confirm_pass=filter_var($confirm_pass, FILTER_SANITIZE_STRING);
    $empty_pass= 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

    if($old_pass != $empty_pass){
        if($old_pass != $prev_pass){
           $error[] = 'Old password not matched!';
        }elseif($new_pass != $confirm_pass){
           $error[] = 'Confirm password not matched!';
        }else{
           if($new_pass != $empty_pass){
              $update_admin_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
              $update_admin_pass->execute([$confirm_pass, $admin_id]);
              $error[] = 'Password updated successfully!';
           }else{
              $error[] = 'Please enter a new password!';
           }
        }
     }else{
        $error[] = 'Please enter old password';
     }
  
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Profile Update</title>
 
<link rel="stylesheet" href="../css/admin.css">
   <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
  
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">

<body>
    <?php 
    include('../partials/admin_header.php');
    ?>
<section class="admin-login">

   <h3 class="heading">Update Profile</h3> 
<form action="" method="post">
   <?php 
            if(isset($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                };
            };
            ?>
    <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
    <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required placeholder="enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    <input type="password" name="old_pass" placeholder="Enter old password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    <input type="password" name="new_pass" placeholder="Enter new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    <input type="password" name="confirm_pass" placeholder="Confirm new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    <input type="submit" value="update now" class="btn" name="update">

</form>

</section>
    <script src="../index.js"></script>
    <script src="../admin/admin.js"></script>
</body>
</html>