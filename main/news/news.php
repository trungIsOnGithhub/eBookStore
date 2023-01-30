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
   <title>NEWS</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>our shop</h3>
   <p> <a href="home.php">HOME</a> / NEWS </p>
</div>

<section class="products">

   <h1 class="title">latest news</h1>

   <div class="box-container_news">

      <?php  
         $select_news = mysqli_query($conn, "SELECT * FROM `news`") or die('query failed');
         if(mysqli_num_rows($select_news) > 0){
            while($fetch_news = mysqli_fetch_assoc($select_news)){
      ?>
     <form action="" method="post" class="box_news">
      <div class="flex">
         <img class="image" style="max-width:50%;" src="uploaded_img/<?php echo $fetch_news['img']; ?>" alt="" onclick="location.href='details_news.php?news_id=<?php echo $fetch_news['id']; ?>';">
         <div class = "content" style="max-width:50%;">
                     <h2><a href="details_news.php?news_id=<?php echo $fetch_news['id']; ?>"><?php echo $fetch_news['title']; ?></a></h2>
                     <p style="margin-top:15px;"><?php echo $fetch_news['content']; ?></p>
         </div>
      </div>
     <!-- <input type="button" value="Details" name="" class="infobtn" onclick="location.href='details_news.php?news_id=<?php echo $fetch_news['id']; ?>';"> -->
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">No products added yet!</p>';
      }
      ?>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>