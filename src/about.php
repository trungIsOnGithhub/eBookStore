<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!--SEO tag-->
   <meta name="description" content="Visit our bookstore, shop latest and most popular books, magazines,...everything readable.
">
   <meta name="description" content="Any thing you want to read is here, books, magazines, or even magazin too...">
   <meta name="description" content="A truly place for a bookworm, indulge reading, bibliophile,.... everything related to books.">
   <meta name="description" content="Looking for a preriodical, journal, issues,... here is what for you.">
   <!--SEO tag-->
   <title>about</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>about us</h3>
   <p> <a href="home.php">HOME</a> / ABOUT </p>
</div>

<section class="about">

   <div class="flex">

      <div style="box-shadow: 5px 5px 5px rgba(251, 200, 142, 0.8);"class="image">
         <img src="images/about2.jpg" alt="">
      </div>

      <div class="content">
         <h3>why choose us?</h3>
         <p></p>
         <p>Nhom Bai Tap Lon mon Lap Trinh Web - lop L04 - Giang vien: Nguyen Huu Hieu - hoc ki 2022_1
            * Nguyen Viet Trung - Nguyen Hoang Tri Vien - Nguyen Hoang Tuan Bao - Nguyen Tuan Anh
            * Dai Hoc Bach Khoa - DHQG TPHCM</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">client's reviews</h1>

   <div class="box-container">

   <?php
$get_comment=mysqli_query($conn,"SELECT * FROM `review` ORDER BY Created_date DESC LIMIT 6");
if(mysqli_num_rows($get_comment)>0) {
   while($fetch_comment=mysqli_fetch_assoc($get_comment)) {
      $ACCID=$fetch_comment['ACCID'];
      $fetch_account=mysqli_query($conn,"SELECT* FROM `account` WHERE Account_ID='$ACCID'") or die ('query failed');
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
            echo'

            <i style="color: rgb(203, 195, 195);"class="fas fa-star"></i>';
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

<section class="authors">
   <?php 
   $get_member = mysqli_query($conn,"SELECT * FROM `company`");
   ?>
   
   <h1 class="title">Our Members</h1>
   <div class="box-container">
      <?php
      if(mysqli_num_rows($get_member)>0) {
      while($fetch_member=mysqli_fetch_assoc($get_member)) {
         $ACCID=$fetch_member['id'];
         $fetch_member=mysqli_query($conn,"SELECT* FROM `company` WHERE id ='$ACCID'") or die ('query failed');
         $fetch_member=mysqli_fetch_array($fetch_member);
         ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_member['img']; ?>" alt="">
         <div class="share">
            <a href="<?php echo $fetch_member['contact']; ?>" class="fab fa-facebook-f"></a>
         </div>
         <h3><?php echo $fetch_member['name'];?></h3>
      </div>
      <?php }?>
   </div>
   <?php }?>

</section>







<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>