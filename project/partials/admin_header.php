
<nav class="background navbar">
        <div class="logo">
            <img src="../Images/mainlogo.png" alt="">
            <h2>wowAdmin</h2>
        </div>
        <ul class="left-nav">
            <li><a href="../admin/admin_page.php">Home</a></li>
            <li><a href="../admin/admin_product.php">Products</a></li>
            <li><a href="../admin/admin_orders.php">Orders</a></li>
            <li><a href="../admin/admin_accounts.php">Admin</a></li>
            <li><a href="../admin/user_accounts.php">User</a></li>
        </ul>
        <div class="icons">
            <i id="user-btn" class="fa fa-user" aria-hidden="true"></i> 
        </div>
        <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?> 
         <p>Username: <?= $fetch_profile['name']; ?></p>
 
         <a href="../admin/admin_profile_update.php" class="btn">update profile</a>
         <a href="../logout.php" class="delete-btn">logout</a>
         <div class="flex-btn">
            <!-- <a href="../admin/admin_login.php" class="option-btn">login</a> -->
            <a href="../admin/admin_register.php" class="option-btn">register</a>
        </div>
</nav>
