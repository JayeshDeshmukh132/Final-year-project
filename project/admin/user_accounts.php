<?php 
include('../config.php');
session_start();
$admin_id =  $_SESSION['admin_id'];

if(!isset($admin_id)){
header('location:admin_login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User accounts</title>
 
<link rel="stylesheet" href="../css/admin.css">
   <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
  
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">

<body>
    <?php 
    include('../partials/admin_header.php');
    ?>
<section class="accounts">

<h1 class="heading">user accounts</h1>

<div class="box-container">

<?php
   $select_accounts = $conn->prepare("SELECT * FROM `user`");
   $select_accounts->execute();
   if($select_accounts->rowCount() > 0){
      while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
?>
<div class="box">
   <p> user id : <span><?= $fetch_accounts['id']; ?></span> </p>
   <p> username : <span><?= $fetch_accounts['name']; ?></span> </p>
   <p> email : <span><?= $fetch_accounts['email']; ?></span> </p>
   <!-- <a href="user_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('delete this account?')" class="delete-btn">delete</a> -->
</div>
<?php
      }
   }else{
      echo '<p class="one" >No accounts available!</p>';
   }
?>

</div>

</section>
   <script src="../index.js"></script>
   <script src="../admin/admin.js"></script>
</body>
</html>