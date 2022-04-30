<?php include'config.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id= $_SESSION['user_id'];
}else{
    $user_id='';
}
if(isset($_POST['order'])){

   if($user_id == ''){
      echo '<script>alert("Please Login First!")</script>';
   }else{
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $number = $_POST['number'];
      $number = filter_var($number, FILTER_SANITIZE_STRING);
      $address = 'flat no.'.$_POST['flat'].', '.$_POST['street'].' - '.$_POST['pin_code'];
      $address = filter_var($address, FILTER_SANITIZE_STRING);
      $method = $_POST['method'];
      $method = filter_var($method, FILTER_SANITIZE_STRING);
      $total_price = $_POST['total_price'];
      $total_products = $_POST['total_products'];

      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);

      if($select_cart->rowCount() > 0){
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $method, $address, $total_products, $total_price]);
         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);
         echo '<script>alert("Order placed successfully!")</script>';
      }else{
         echo '<script>alert("Your cart is empty!")</script>';
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
  <?php include('partials/header.php') ?>

   <!-- order section starts  -->

<section class="checkout" id="checkout">

    
    <form action="" method="post">
        <h1 class="heading">order now</h1>
 
       <div class="display-orders">
       <?php
         $grand_total = 0;
         $cart_item[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
              $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
              $grand_total += $sub_total; 
              $cart_item[] = $fetch_cart['name'].' ( '.$fetch_cart['price'].' x '.$fetch_cart['quantity'].' ) - ';
              $total_products = implode($cart_item);
              echo '<p>'.$fetch_cart['name'].' <span>(₹'.$fetch_cart['price'].' x '.$fetch_cart['quantity'].')</span></p>';
            }
         }else{
            echo '<p class="empty"><span>your cart is empty!</span></p>';
         }
      ?>
       </div>
       <div class="grand-total"> grand total : ₹<?= $grand_total; ?>/-</div>
       <input type="hidden" name="total_products" value="<?= $total_products; ?>">
      <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
       <div class="flex">
          <div class="inputBox">
             <span>Your name :</span>
             <input type="text" name="name" class="box" required placeholder="Enter your name" maxlength="20">
          </div>
          <div class="inputBox">
             <span>Your number :</span>
             <input type="number" name="number" class="box" required placeholder="Enter your number" min="0">
          </div>
          <div class="inputBox">
             <span>payment method</span>
             <select name="method" class="box">
                <option value="cash on delivery">cash on delivery</option>
             </select>
          </div>
          <div class="inputBox">
             <span>Address line 01 :</span>
             <input type="text" name="flat" class="box" required placeholder="e.g. flat no, bulding name" maxlength="50">
          </div>
          <div class="inputBox">
             <span>address line 02 :</span>
             <input type="text" name="street" class="box" required placeholder="e.g. street name." maxlength="50">
          </div>
          <div class="inputBox">
             <span>pin code :</span>
             <input type="number" name="pin_code" class="box" required placeholder="e.g. 123456" min="0">
          </div>
       </div>
 
       <input type="submit" value="order now" class="btn" name="order">
 
    </form>
 
 </section>
 
 <!-- order section ends -->
  
    <script src="index.js"></script>
</body>

</html>