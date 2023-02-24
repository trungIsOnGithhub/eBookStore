<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_news'])){

   $title = mysqli_real_escape_string($conn, $_POST['title']);
   $content = mysqli_real_escape_string($conn,$_POST['content']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_news_title = mysqli_query($conn, "SELECT `title` FROM `news` WHERE title = '$title'") or die('query failed');

   if(mysqli_num_rows($select_news_title) > 0){
      $message[] = 'news already added';
   }else{
      $add_news_query = mysqli_query($conn, "INSERT INTO `news`(img,title,content) VALUES('$image','$title', '$content')") or die('query failed');
      if($add_news_query){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'news added successfully!';
         }
      }else{
         $message[] = 'news could not be added!';
      }
   }
}

#ADD END HERE

#DELETE

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT img FROM `news` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['Thumbnail']);

   mysqli_query($conn,"DELETE FROM `news` WHERE id ='$delete_id'") or die ("query failed");
   header('location:admin_news.php');
}

if(isset($_POST['update_news'])){

   $update_p_id = $_POST['update_p_id'];
   $update_title = mysqli_real_escape_string($conn,$_POST['update_title']);
   $update_content= mysqli_real_escape_string($conn,$_POST['update_content']);
   mysqli_query($conn, "UPDATE `news` SET title = '$update_title', content = '$update_content' WHERE id = '$update_p_id'") or die('query failed here');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `news` SET img = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_news.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>all news</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">shop news</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add news</h3>
      <input type="text" name="title" class="box" placeholder="Enter title" required>
      <textarea style="background-color: var(--light-bg); border-radius: 0.5rem; margin: 1rem 0; padding: 1.2rem 1.4rem; color: var(--black);border: var(--border); font-size:1.8rem; width: 100%;" name="content" cols="100" rows="8" placeholder="Enter content" ></textarea>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add news" name="add_news" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_news_id = mysqli_query($conn,"SELECT `id` FROM `news`") or die ('query failed');
         if(mysqli_num_rows($select_news_id) > 0){            
            while($fetch_news= mysqli_fetch_assoc($select_news_id)){
               $pid=$fetch_news['id'];

               $fetch_news = mysqli_query($conn,"SELECT * FROM `news` WHERE id='$pid'");
               $fetch_news = mysqli_fetch_array($fetch_news);
      ?>
      <div class="box">
         <img style="max-width:100%;" src="uploaded_img/<?php echo $fetch_news['img']; ?>" alt="">
         <div class="name"><?php echo $fetch_news['title']; ?></div>
         <a href="admin_news.php?update=<?php echo $fetch_news['id']; ?>" class="option-btn">update</a>
         <a href="admin_news.php?delete=<?php echo $fetch_news['id']; ?>" class="delete-btn" onclick="return confirm('delete this news?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form" style="padding: 10px;">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `news` WHERE id = '$update_id' ") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <!-- Get news id-->
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <!-- Get news thumbnail direction -->
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['img']; ?>">
      <!-- Show thumbnail -->
      <img style="height: 150px; "src="uploaded_img/<?php echo $fetch_update['img']; ?>" alt="">

      <!--get Tile -->
      <input type="text" name="update_title" value="<?php echo $fetch_update['title']; ?>" class="box" required placeholder="enter title">
      <!--get Content -->
      <textarea class="box" rows = "2" cols = "40" name = "update_content" required><?php echo $fetch_update['content']; ?></textarea>
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_news" class="btn">
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

<script src="js/admin_script.js"></script>
<script>
   document.querySelector('#close-update').onclick = () =>{
   document.querySelector('.edit-product-form').style.display = 'none';
   window.location.href = 'admin_news.php';
}
</script>

</body>
</html>