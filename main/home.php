<?php
include 'config.php';
require 'inherit/dbc.php';
require 'inherit/data_abstract.php';
require 'home_mvc/home_model.php';
require 'home_mvc/home_view.php';
require 'home_mvc/home_controller.php';

session_start();

//GLOBAL VARIABLES
$user_id = session_check_if_not_logout($_SESSION, 'user_id');
$main_data_container_array = array();

//MVC model
$main_view = new HomePageView();
$main_models = new HomePageModel($user_id);
$main_controller = new HomePageController();

// in progress
// if(isset($_REQUEST['get'])){
//    mysqli_query($conn, "INSERT INTO `discount_code` (Discount, Expiration_date, Name, ACC_ID) VALUES ('0.2', '2022-12-30', 'Giam 20% cho ban moi', '$user_id')") or die('query failed');
//    $message[] = 'voucher added to cart!';
// }
// if(isset($user_id)){
//    $check = mysqli_query($conn, "SELECT * FROM `discount_code` WHERE name = 'Giam 20% cho ban moi' AND ACC_ID = '$user_id'") or die('query failed');
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HOME</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php
   include 'header.php';
   $main_view->display_add_to_cart_message($main_models, $main_controller);
?>

<section class="home">

   <div class="content">
      <h3>Books & Magazines to your door.</h3>
      <p>Our destiny is bringing the greatest experience about reading to you. Enjoy with us now</p>
      <a href="about.php" class="white-btn">discover more</a></br>
      <?php //if(mysqli_num_rows($check)==0){ ?>
      <!-- <a style="margin:auto;margin-top:10px;width:320px;display:flex; justify-content:center; align-items: center;"href="home.php?get=<?php echo $user_id?>" class="infoobtn" id="new_customer"><span>Mã giảm giá 20% cho bạn mới.</span> <img style="width:10%;"src="uploaded_img/voucher_remove.png" alt=""></a> -->
      <?php //} ?>
   </div>

</section>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

      <?php
         $query_mode = 1;//this part will only in query mode number 1
         $len_fetched = $main_models->get_array_data($main_data_container_array, $query_mode, 10);

         $curr_start_idx = 0;
         $curr_end_idx = $len_fetched;
         $jump = 1;
         //3 lines above will help travel the array we got from the DB

         if($len_fetched > 0) {
         for($i = $curr_start_idx; $i != $curr_end_idx; $i=$i+$jump) {
            $fetch_products = $main_data_container_array[$i];
      ?>
     <form action="" method="POST" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['Thumbnail']; ?>" alt="" onclick="location.href='details.php?product_id=<?php echo $fetch_products['Product_ID']; ?>';">
      <div class="name"><?php echo $fetch_products['Name']; ?></div>
      
      <?php $main_view->display_books_price($fetch_products); ?>

      <input type="number" min="1" name="product_quantity" value="1" class="qty">
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['Name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['Price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['Thumbnail']; ?>">
      <input type="hidden" name="product_id" value="<?php echo $fetch_products['Product_ID']; ?>" >

      <input type="submit" value="add to cart" name="add_to_cart" class="btn">
     </form>
      <?php
         }
      }
      else { echo '<p class="empty">No products added yet!</p>'; }
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="books.php" class="option-btn">More...</a>
   </div>

</section>

<section class="about">
   <div class="flex">
      <div class="image">
         <img src="images/about2.jpg" alt="introduction image">
      </div>
      <div class="content">
         <h3>about us</h3>
         <p>Nhom Bai Tap Lon mon Lap Trinh Web - lop L04 - Giang vien: Nguyen Huu Hieu - hoc ki 2022_1
            * MSSV: 2014887 - 2015043 - 2012667 - 2012609
            * Dai Hoc Bach Khoa - DHQG TPHCM</p>
         <a href="about.php" class="btn">read more</a>
      </div>
   </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>