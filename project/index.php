<?php include'config.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id= $_SESSION['user_id'];
}else{
    $user_id='';
}
if(isset($_POST['update_qty'])){
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['s-qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$qty, $cart_id]);
    echo '<script>alert("Cart qunatity update!")</script>';
 }
if(isset($_GET['delete_cart_item'])){
    $delete_cart_id = $_GET['delete_cart_item'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$delete_cart_id]);
    header('location:index.php');
 }
if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header('location:index.php');
 }
 if(isset($_POST['food-submit'])){

    if($user_id == ''){
      echo '<script>alert("Please Login First!")</script>';
       
    }else{
 
       $pid = $_POST['pid'];
       $name = $_POST['name'];
       $price = $_POST['price'];
       $image = $_POST['image'];
       $qty = $_POST['s-qty'];
       $qty = filter_var($qty, FILTER_SANITIZE_STRING);
 
       $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND name = ?");
       $select_cart->execute([$user_id, $name]);
 
       if($select_cart->rowCount() > 0){
        echo '<script>alert("Already added to cart!")</script>';
       }else{
          $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
          $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
          echo '<script>alert("Added to cart!")</script>';
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
    <?php 
    include ('config.php');
    ?>
    <!-- NAVBAR SECTION STARTS -->
    <nav class="background navbar">
        <div class="logo">
            <img src="Images/mainlogo.png" alt="">
            <h2>wowFood</h2>

        </div>
        <ul class="left-nav">
            <li><a class="active" href="#box1">Home</a></li>
            <li><a href="#features">About us</a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="#contact">Contact us</a></li>
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
            <i id="order" class="fa fa-box" aria-hidden="true"></i>
            <i id="shop" class="fa fa-shopping-cart" aria-hidden="true"><span>(<?= $total_cart_items; ?>)</span></i>
        </div>
        <div class="mobile">
            <i id="search-resp" class="fa fa-search" aria-hidden="true"></i>
            <i id="login-resp" class="fa fa-user" aria-hidden="true"></i>
            <i id="order-resp" class="fa fa-box" aria-hidden="true"></i>
            <i id="shop-resp" class="fa fa-shopping-cart" aria-hidden="true"><span>(<?= $total_cart_items; ?>)</span></i>
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
        <!-- ORDERS STARTS -->
        <div class="my-orders">
            <h3>My Orders</h3>
            <div id="order-close" class="fas fa-times"></div>
            <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){   
      ?>
            <div class="box">
                <p> <span>Placed On : </span><?= $fetch_orders['placed_on']; ?></p>
                <p> <span> Name : </span><?= $fetch_orders['name']; ?></p>
                <p> <span> Number :  </span><?= $fetch_orders['number']; ?></p>
                <p> <span> Address : </span><?= $fetch_orders['address']; ?></p>
                <p> <span> Payment Method : </span><?= $fetch_orders['method']; ?></p>
                <p> <span> Your Orders : </span><?= $fetch_orders['total_products']; ?></p>
                <p> <span> Total Price : </span>₹<?= $fetch_orders['total_price']; ?>/-</p>
                <p> <span> Payment Status : </span> <?= $fetch_orders['payment_status']; ?></p>
            </div>
            <?php
         }
      }else{
         echo '<p class="empty">Nothing ordered yet!</p>';
      }
      ?>
        </div>
        <!-- ORDERS ENDS -->
       <!-- SHOPPING CART FORM STARTS -->
       <div class="shopping-cart">
           <div class="fas fa-times" id="shopping-close"></div>
        <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
              $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
              $grand_total += $sub_total; 
             
      ?>
            <div class="box">
                <a href="index.php?delete_cart_item=<?= $fetch_cart['id']; ?>" class="fas fa-trash" onclick="return confirm('Delete this cart item?');"></a>
                <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
                <div class="s-content">
                    <h3><?= $fetch_cart['name']; ?> </h3>
                    <form action="" method="post">
                    <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                        <input type="number" name="s-qty" min="1" max="100" value="<?= $fetch_cart['quantity']; ?>" onkeypress="if(this.value.length == 2) return false;" class="quantity">
                        <button type="submit" class="fas fa-edit" name="update_qty" ></button>
                    </form>
                </div>
            </div>
            <?php
         }
      }else{
         echo '<p class="empty"><span>Your cart is empty!</span></p>';
      }
      ?>
            <div class="total">Total: ₹<?= $grand_total; ?>/-  </div>
            <a href="checkout.php" class="btn">Checkout</a>
        </div>
        <!-- SHOPPING CART FORM ENDS -->
    </nav>

    <!-- NAVBAR SECTION ENDS -->
    <!-- SECTION 1 STARTS -->
    <section class="box1" id="box1">

    </section>
    <!--  SECTION 1 ENDS -->

    <!-- FEATURE SECTION STARTS -->
    <section class="features" id="features">
        <h1 class="f-heading">Our<span>Features</span></h1>
        <div class="box-container">
            <div class="box" >
                <img src="Images/feature-img-1.png" alt="">
                <h3>Fresh and organic</h3>
                <p>100% Fresh and organic ingredients are used in our food products</p>
            </div>
            <div class="box" >
                <img src="Images/feature-img-2.png" alt="">
                <h3>Free delivery</h3>
                <p>No delivery fee will be charged on ordered food</p>
            </div>
            <div class="box" >
                <img src="Images/feature-img-3.png" alt="">
                <h3>Fresh and organic</h3>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Doloribus, reprehenderit.</p>
            </div>
        </div>
    </section>
    <!-- FEATURE SECTION ENDS -->

    <!-- Menu section starts -->
    <section class="menu" id="menu">
        <h1 class="f-heading">Our<span>Menu</span></h1>
        <div class="box-container">
        <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){    
      ?>
            <div class="box">
                <div class="price">₹<span><?= $fetch_products['price'] ?></span>/-</div>
                <div class="food-name"><?= $fetch_products['name'] ?></div>
                <img src="uploaded_img/<?= $fetch_products['image'] ?>" alt="">
                <form action="" method="post">
                <input type="hidden" name="pid" value="<?= $fetch_products['id'] ?>">
            <input type="hidden" name="name" value="<?= $fetch_products['name'] ?>">
            <input type="hidden" name="price" value="<?= $fetch_products['price'] ?>">
            <input type="hidden" name="image" value="<?= $fetch_products['image'] ?>">
                    <input type="number" min="1" max="100" value="1" onkeypress="if(this.value.length == 2) return false;" class="qty" name="s-qty">
                    <input type="submit" name="food-submit" value="Add to cart" class="btn">
                </form>
            </div>
            <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>

        </div>

    </section>
    <!-- Menu section ends -->

    <script src="index.js"></script>
</body>

</html>