<?php 

if(isset($_SESSION['user_id'])){
    $user_id= $_SESSION['user_id'];
}else{
    $user_id='';
}
if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header('location:index.php');
 }
?>
    <!-- NAVBAR SECTION STARTS -->
    <nav class="background navbar">
        <div class="logo">
            <img src="Images/mainlogo.png" alt="">
            <h2>wowFood</h2>

        </div>
        <ul class="left-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php">About us</a></li>
            <li><a href="index.php">Menu</a></li>
            <li><a href="index.php">Contact us</a></li>
            <a href="#" id="close"><i class="far fa-times"></i></a>
        </ul>
        <div class="icons">
        <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
            <i id="search-btn" class="fa fa-search" aria-hidden="true"></i>
            <i id="login" class="fa fa-user" aria-hidden="true"></i>
           
        </div>
        <div class="mobile">
            <i id="search-resp" class="fa fa-search" aria-hidden="true"></i>
            <i id="login-resp" class="fa fa-user" aria-hidden="true"></i>
            
            <i id="bars-resp" class="fa fa-bars" aria-hidden="true"></i>
        </div>
  <!-- SEARCH BOX STARTS -->
  <form action="" method="post" class="search-box">
            <input type="text" class="search-bar" name="search" id="search" placeholder="Search here...">
            <button class="search-bar" id="submit-btn" type="submit"><i class="fas fa-search"
                    aria-hidden="true"></i></button>
        </form>
        <!-- SEARCH BOX ENDS -->
         <!-- LOGIN FORM STARTS -->
       <form action="user_login.php" method="post" class="login-form">
        <?php
            $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
            $select_user->execute([$user_id]);
            if($select_user->rowCount() > 0){
               while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
                  echo '<p class="error-msg">Welcome! <span>'.$fetch_user['name'].'</span></p>';
                  echo '<a href="index.php?logout" name="out" class="btn">logout</a>';
                 
               }
            }else{
               echo '<p><span class="error-msg">You are not logged in now!</span></p>';
            }
         ?>
            <div class="fas fa-times" id="login-close"></div>
            <?php
            if(isset($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                };
            };
            ?>
            <h3>login now</h3>
            <input type="email" name="login-name" placeholder="Enter your email" class="box" required>
            <input type="password" name="login-pass" placeholder="Enter your password" class="box" required>
            <p>Don't have a account? <a href="login.php">Create now</a></p>
            <p>Are you a admin? <a href="admin/admin_login.php">Login as admin</a></p>
            <input type="submit" name="login-submit" value="login now" class="btn">
        </form>

        <!-- LOGIN FORM ENDS -->
        
    </nav>
    <!-- NAVBAR SECTION ENDS -->