<?php require 'notification.php'; ?>

<header class="header" class="message">
   <div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
         </div>
         <p><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i></a></p>
      </div>
   </div>

   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo">GROUP 3</a>

         <nav class="navbar">
            <a href="home.php">HOME</a>
            <a href="about.php">ABOUT</a>
            <a href="books.php">BOOKS</a>
            <a href="contact.php">CONTACT</a>
            <a href="orders.php">ORDER</a>
            <a href="news.php">NEWS</a>
         </nav>

         <div class="icons" style="display:flex;flex-direction:row;">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <!--Trung code avatar picture 12/12/2022-->
            <?php
               $select_image = mysqli_query($conn, "SELECT Image FROM `account` WHERE Account_ID = '".$_SESSION["user_id"]."'") or die("image query failed");
               $img_info = mysqli_fetch_array($select_image);
               if(array_key_exists("Image",$img_info) && $img_info["Image"]){echo '<div id="user-btn" class="ava-pic"><img loading="lazy" src="'.$image_foldr.$img_info["Image"].'"></div>';}
               else{echo '<div id="user-btn" class="fas fa-user"></div>';}
            ?>
            <style>.ava-pic>img{height:3.5rem;width:3.5rem;border-radius:50%;}</style>
            <!--Trung code avatar picture 12/12/2022-->
            <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <div class="user-box">
            <p>User name : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>Email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <?php echo '<a href="info_edit.php?id='.$_SESSION["user_id"].'" class="btn">Edit Info</a>'; ?>
         </div>
      </div>
   </div>
</header>