<?php

include '../config.php';

session_start();

if(isset($_POST['login'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['id'];
      header('location:admin_page.php');
      echo '<script>alert("Login successful!")</script>';
   }else{
      $error[] = 'Incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin login</title>
   <link rel="stylesheet" href="../css/admin.css">

   <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="../css/style.css">
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">

</head>
<body>

<nav class="background navbar">
        <div class="logo">
            <img src="../Images/mainlogo.png" alt="">
            <h2>wowFood</h2>

        </div>
        <ul class="left-nav">
            <li><a href="../index.php">Home</a></li>
            <li><a href="../index.php">About us</a></li>
            <li><a href="../index.php">Menu</a></li>
            <li><a href="../index.php">Contact us</a></li>
            <a href="#" id="close"><i class="far fa-times"></i></a>
        </ul>
        <div class="icons">
            <i id="login" class="fa fa-user" aria-hidden="true"></i>
        </div>
        <div class="mobile">
            <i id="login-resp" class="fa fa-user" aria-hidden="true"></i>
            <i id="bars-resp" class="fa fa-bars" aria-hidden="true"></i>
        </div>
        </nav>

<section class="admin-login">

   <form action="" method="post">
      <h3>login now</h3>
<?php 
            if(isset($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                };
            };
            ?>
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" class="btn" name="login">
   </form>

</section>

<script src="../index.js"></script>
</body>
</html>