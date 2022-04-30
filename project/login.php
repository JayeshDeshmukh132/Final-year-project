<?php 
include('config.php');

session_start();
include('partials/header.php');

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['register-submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['password']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpassword'] );
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `user` WHERE name = ? AND email = ?");
   $select_user->execute([$name, $email]);

   if($select_user->rowCount() > 0){
      $error[] = 'Username or email already exists!';
   }else{
      if($pass != $cpass){
         $error[] = 'Password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `user`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $error[] = 'Registered successfully, login now please!';
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
   
    <title>wowFood</title>
</head>

<body>
 <div class="form-container1">
     <form action=""  method="post">
            <h3>Register Now</h3>
            <input type="text" name="name" id="name" placeholder="Enter your name" required>
            <input type="email" name="email" id="email" placeholder="Enter your email" required >
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
            <input type="password"name="cpassword" id="cpassword" placeholder="Confirm your password" required> 
            <input type="submit" name="register-submit" value="Register now" class="form-btn">
           <p>Already have a account?<a href="index.html"> Login now</a></p>
        </form>
 </div>
 <div class="form-container">
     <form action=""  method="post">
            <h3>Register Now</h3>
            <?php 
            if(isset($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                };
            };
            ?>
            <input type="text" name="name" id="name" placeholder="Enter your name" required>
            <input type="email" name="email" id="email" placeholder="Enter your email" required >
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
            <input type="password"name="cpassword" id="cpassword" placeholder="Confirm your password" required> 
            <input type="submit" name="register-submit" value="Register now" class="form-btn">
           <p>Already have a account?<a href="index.php"> Login now</a></p>
        </form>
 </div>


<script src="index.js"></script>
</body>

</html>