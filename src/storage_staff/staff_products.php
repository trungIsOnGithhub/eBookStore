<?php

include '../config.php';

session_start();

$admin_id = $_SESSION['staff_id'];

if(!isset($admin_id)){
   header('location:../login.php');
};

if(isset($_POST['add_product'])){
   $type=$_POST['product_type'];
   if($type=="book") {
      header('location:staff_products_book.php');
   }
   else {
      header('location:staff_products_magazine.php');
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT Thumbnail FROM `product` WHERE Product_ID = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('../uploaded_img/'.$fetch_delete_image['Thumbnail']);
   mysqli_query($conn, "DELETE FROM `product` WHERE Product_ID = '$delete_id'") or die('query failed');
   header('location:staff_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_description=$_POST['update_description'];

   mysqli_query($conn, "UPDATE `product` SET Name = '$update_name', Price = '$update_price', Description='$update_description' WHERE Product_ID = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = '../uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `product` SET Thumbnail = '$update_image' WHERE Product_ID = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('../uploaded_img/'.$update_old_image);
      }
   }

   header('location:staff_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>all products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      .add-products form select
{
   border:var(--border);
   width: 100%;
   border-radius: .5rem;
   width: 100%;
   background-color: var(--white);
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   margin:1rem 0;
}
</style>

</head>
<body>
   
<?php include 'staff_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">all shop products</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add product | choose type</h3>
      <select name="product_type">
               <option value="book">book</option>
               <option value="magazine">magazine</option>
      </select>
      
      <input type="submit" value="add product" name="add_product" class="btn">
      
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `product`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img style="max-width:100%;" src="../uploaded_img/<?php echo $fetch_products['Thumbnail']; ?>" alt="">
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
         <a href="staff_products.php?update=<?php echo $fetch_products['Product_ID']; ?>" class="option-btn">update</a>
         <a href="staff_products.php?delete=<?php echo $fetch_products['Product_ID']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `product` WHERE Product_ID = '$update_id' ") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <!-- Get product id-->
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['Product_ID']; ?>">
      <!-- Get product thumbnail direction -->
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['Thumbnail']; ?>">
      <!-- Show thumbnail -->
      <img src="../uploaded_img/<?php echo $fetch_update['Thumbnail']; ?>" alt="">

      <!--get Name -->
      <input type="text" name="update_name" value="<?php echo $fetch_update['Name']; ?>" class="box" required placeholder="enter product name">
      <!-- Get price -->
      <input type="number" name="update_price" value="<?php echo $fetch_update['Price']; ?>" min="0" class="box" required placeholder="enter product price">
      <!--get publisher-->
      <input type="text" name="update_publisher" value="<?php echo $fetch_update['Publisher']; ?>" class="box" required placeholder="enter publisher">
      <textarea class="box" rows = "4" cols = "40" name = "update_description" required><?php echo $fetch_update['Description']; ?></textarea>
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>





<!-- custom admin js file link  -->
<script src="../js/admin_script.js"></script>
<script>
   document.querySelector('#close-update').onclick = () =>{
   document.querySelector('.edit-product-form').style.display = 'none';
   window.location.href = 'staff_products.php';
}
</script>
</body>
</html>