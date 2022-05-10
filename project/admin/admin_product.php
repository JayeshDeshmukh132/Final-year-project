<?php 
include('../config.php');
session_start();
$admin_id =  $_SESSION['admin_id'];

if(!isset($admin_id)){
header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $select_product = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_product->execute([$name]);

   if($select_product->rowCount() > 0){
      $error[] = 'Product name already exist!';
   }else{
      if($image_size > 2000000){
         $error[] = 'Image size is too large!';
      }else{
         $insert_product = $conn->prepare("INSERT INTO `products`(name, price, image) VALUES(?,?,?)");
         $insert_product->execute([$name, $price, $image]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $error[] = 'New product added!';
      }
   }

}
if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_product.php');
   if($delete_product->rowCount()>0){
      $error[] = 'Product deleted successfully';
   }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>
 
<link rel="stylesheet" href="../css/admin.css">
   <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
  
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">

<body>
    <?php 
    include('../partials/admin_header.php');
    ?>
<section class="add-products">

   <h1 class="heading">add product</h1>
   
   <form action="" method="post" enctype="multipart/form-data">
   <?php 
            if(isset($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                };
            };
            ?>
      <input type="text" class="box" required maxlength="100" placeholder="Enter product name" name="name">
      <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>

</section>
<section class="display-product-table">
<h1 class="heading">added product</h1>
   <table>

      <thead>
         <th>product image</th>
         <th>product name</th>
         <th>product price</th>
         <th>action</th>
      </thead>

      <tbody>
      <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>

         <tr>
            <td><img src="../uploaded_img/<?= $fetch_products['image']; ?>"  alt=""></td>
            <td><?= $fetch_products['name']; ?></td>
            <td>₹<?= $fetch_products['price']; ?>/-</td>
            <td>
               <a href="admin_product.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('are your sure you want to delete this?');"> <i class="fas fa-trash"></i> delete </a>
               <a href="admin_product_update.php?update=<?= $fetch_products['id']; ?>" class="option-btn"> <i class="fas fa-edit"></i> update </a>
            </td>
         </tr>

         <?php
            };    
            }else{
               echo "<div class='empty'>no product added</div>";
            };
         ?>
      </tbody>
   </table>

</section>
   <script src="../index.js"></script>
   <script src="../admin/admin.js"></script>
</body>
</html>