<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

$product_id=$_REQUEST['product_id'];

$select_p=mysqli_query($conn,"SELECT * FROM `product` WHERE Product_ID='$product_id'") or die ('query failed');
if(mysqli_num_rows($select_p)>0) {
$select_product=mysqli_fetch_assoc($select_p);
}

if(isset($_POST['comment'])){

   
   $ACCID=$user_id;
   $Product_ID=$product_id;
   $Content=$_POST['Content'];
   $Rating=$_POST['Rating'];   

   // $image = $_FILES['image']['name'];
   // $image_size = $_FILES['image']['size'];   
   // $image_tmp_name = $_FILES['image']['tmp_name'];
   // $image_folder = 'uploaded_img/'.$image;

   

   $add_comment=mysqli_query($conn,"INSERT INTO `review` (ACCID,Product_ID,Content,Rating) VALUES ('$ACCID','$Product_ID','$Content','$Rating')") or die ('query failed');

   

   // if($add_comment){
   //    if($image_size > 5000000){
   //       $message[] = 'image size is too large';
   //    }else{
   //       move_uploaded_file($image_tmp_name, $image_folder);
   //       $message[] = 'You have commented successfully !';
   //    }
   // }else{
   //    $message[] = 'Cannot comment !';
   // }
   
}

if(isset($_POST['add_to_cart'])){

   $product_name = $select_product['Name'];
   $product_price = $select_product['Discount_price'];
   $image=$select_product['Thumbnail'];
   $product_quantity = $_POST['product_quantity'];
   $product_id=$select_product['Product_ID'];

  


   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id,Product_ID, name, price, quantity, image) VALUES('$user_id','$product_id', '$product_name', '$product_price', '$product_quantity', '$image')") or die('query failed');
      $message[] = 'product added to cart!';
   }
   

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
   <title><?php echo $select_product['Name']; ?></title>
    

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .about .flex .content span{
   padding:1rem 0;
   line-height: 2;
   font-size: 1.7rem;
   color:var(--light-color);
   text-overflow:ellipsis;
}

</style>

   

</head>
<body>
   
<?php include 'header.php'; ?>
<div class="heading">
   <h3>our shop</h3>
   <p> <a href="home.php">HOME</a> / DETAIL </p>
</div>



<section class="about">

   <div class="flex">
      <div class="image" style="width:50%;margin-bottom: 30px; background-color: var(--light-color); border-radius: 30px;">
         <img style="display: block; margin-top: 20px; margin-bottom: 20px; margin-left: auto; margin-right: auto; height: 500px; width: auto;"src="uploaded_img/<?php echo $select_product['Thumbnail'];?>" alt="">
      </div>
      <form style="width:50%;"action="" method="post">
      <div class="content" style="padding-left: 30px;">
         <h3><?php echo $select_product['Name'];?></h3>
         <span><?php echo $select_product['Description']?></span>
         <?php
         $fetch_book=mysqli_query($conn,"SELECT * FROM `book` WHERE Product_ID='$product_id'") or die ('query failed');
         if(mysqli_num_rows($fetch_book)>0) {
            $fetch_book=mysqli_fetch_array($fetch_book);
            $quantity=$fetch_book['Quantity_in_store'];
            echo "<p><b>Quantity: </b>".$quantity. "</p>";
         }
         else {
            $fetch_magazine=mysqli_query($conn,"SELECT * FROM `magazine_seri` WHERE Product_ID='$product_id'") or die ('query failed');
            $fetch_magazine=mysqli_fetch_array($fetch_magazine);
            $duration=$fetch_magazine['Duration'];
            echo "<p><b>Duration: </b>". $duration. " month </p>";
         }
         ?>
         

         <p><b>Publisher: </b><?php echo $select_product['Publisher']; ?></p>
         <p><b>Author: </b><?php 
         $select_author=mysqli_query($conn,"SELECT * FROM `writee` WHERE PRODUCT_ID='$product_id'") or die ('query failed');
         while($fetch_author=mysqli_fetch_assoc($select_author)) {
            $a_id=$fetch_author['AUTHOR_ID'];
            $get_author=mysqli_query($conn,"SELECT * FROM `author` WHERE Author_ID='$a_id'") or die ('query failed');
            $get_author=mysqli_fetch_array($get_author);

            echo $get_author['Fullname'].", ";
         } 
         ?></p>
         <p>
         
         <b>Rating: </b>
            <?php 
            $star=0;
            $sumofcomment=0;
            $sumofrate=0;
            $getRate=mysqli_query($conn,"SELECT * FROM `review` WHERE Product_ID='$product_id'") or die ('query failed');
            if(mysqli_num_rows($getRate)) {
               while($cRate=mysqli_fetch_assoc($getRate)) {
                  $sumofcomment++;
                  $sumofrate+=$cRate['Rating'];
               }
            }
            if($sumofcomment) {
               $star=($sumofrate/$sumofcomment);
            }
            $s=0;
            while($star>0){
               echo"
            <i class='fas fa-star'></i>";
             $star--;
             $s++;           
           }
            $non=5-$s;
            while($non>0){
            echo'

            <i style="color: rgb(203, 195, 195);"class="fas fa-star"></i>';
            $non--;

            }
            ?>
         
         </p>
         <input type="number" min="1" name="product_quantity" value="1" style="font-size: 30px; margin-bottom: 10px; padding-left: 15px; border-radius: .5rem;">
         <h3>Price: <?php 
         $price=$select_product['Price'];
         $discount_price=$select_product['Discount_price'];
         if($discount_price) {
            echo "<s style='text-decoration: line-through'>$".$price."</s>";
            echo " | $".$discount_price;
         }
         else {
            echo "$".$price;
         }
          ?></h3>
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </div>
      </form>

   </div>

</section>

<section class="reviews">

   <h1 class="title">reviews</h1>

   <div class="box-container">
   <?php
$get_comment=mysqli_query($conn,"SELECT * FROM `review` WHERE Product_ID='$product_id' ORDER BY Created_date DESC LIMIT 3");
if(mysqli_num_rows($get_comment)>0) {
   while($fetch_comment=mysqli_fetch_assoc($get_comment)) {
      $ACCID=$fetch_comment['ACCID'];
      $fetch_account=mysqli_query($conn,"SELECT * FROM `account` WHERE Account_ID='$ACCID'") or die ('query failed');
      $fetch_account=mysqli_fetch_array($fetch_account);
?>

      <div class="box">
         <?php
            if(array_key_exists('Image',$fetch_account) && $fetch_account['Image']){echo '<img src="'.$image_foldr.$fetch_account['Image'].'" alt="">';}
            else{echo '<img src="'.$image_foldr.$default_img.'" alt="avatar picture">';}
         ?>
         <p><?php echo $fetch_comment['Content']; ?></p>
         <div class="stars">
            <?php $star=$fetch_comment['Rating']; 
            while($star>0){
               echo"
            <i class='fas fa-star'></i>";
             $star--; ?>            
            
            <?php }
            $non=5-$fetch_comment['Rating'];
            while($non>0){
               echo '<i style="color: rgb(203, 195, 195);"class="fas fa-star"></i>';
               $non--;
            }
            ?>


         </div>
         <h3><?php echo $fetch_account['FName']." ".$fetch_account['LName']; ?></h3>
      </div>
      <?php
   }
}
?>      
   </div>

</section>



<section class="checkout">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Comment here</h3>
      <div class="flex">         
         <div class="inputBox">
            <span>Your comment :</span>
            <input type="text" name="Content" placeholder="Content">
         </div>
         <div class="inputBox">
            <span>Rating :</span>
            <input type="number" min="1" max="5" name="Rating" placeholder="choose rating">
         </div>
         <!-- <div class="inputBox">
         <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
         </div> -->
      </div>
      <input type="submit" value="comment" class="btn" name="comment">
      
   </form>

</section>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>