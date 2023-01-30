<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel</title>

   <!-- bootstrap -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- admin dashboard section starts  -->
<div class = "home">
   <div class="heading">
         <h3>WELCOME TO YOUR ADMIN PANEL</h3>
   </div>
</div>

<section class="products">

   <h1 class="title">Your products</h1>
   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `product`") or die('query failed');
         $count=0;
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['Thumbnail']; ?>" alt="">
      <div class="name"><?php echo $fetch_products['Name']; ?></div>
      <div class="price"><?php 
         $price=$fetch_products['Price'];
         $discount_price=$fetch_products['Discount_price'];
         if($discount_price) {
            echo "<s style='text-decoration: line-through'>$".$price."</s>";
            echo " | $".$discount_price;
         }
         else {
            echo "$".$price;
         }
          ?></div>
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['Name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['Price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['Thumbnail']; ?>">
      <input type="hidden" name="product_id" value="<?php echo $fetch_products['Product_ID']; ?>" >
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
   </div>

</section>

<!-- admin dashboard section ends -->









<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>