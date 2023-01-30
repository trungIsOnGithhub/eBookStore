<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_info'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $contact = mysqli_real_escape_string($conn,$_POST['contact']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

 
    $add_news_query = mysqli_query($conn, "INSERT INTO `company`(name,img,contact) VALUES('$name','$image', '$contact')") or die('query failed');
    if($add_news_query){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'member added successfully!';
         }
    }else{
         $message[] = 'member could not be added!';
    }
   
}

#ADD END HERE

#DELETE

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT img FROM `company` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['img']);

   mysqli_query($conn,"DELETE FROM `company` WHERE id ='$delete_id'") or die ("query failed");
   header('location:admin_company.php');
}

if(isset($_POST['update_member'])){
   $update_p_id = $_POST['update_p_id'];
   $update_name = mysqli_real_escape_string($conn,$_POST['update_name']);
   $update_contact= mysqli_real_escape_string($conn,$_POST['update_contact']);
   mysqli_query($conn, "UPDATE `company` SET name = '$update_name', contact = '$update_contact' WHERE id = '$update_p_id'") or die('query failed here');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `company` SET img = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_company.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Company</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">Our Members</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add info</h3>
      <input type="text" name="name" class="box" placeholder="Enter name" required>
      <textarea style="background-color: var(--light-bg); border-radius: 0.5rem; margin: 1rem 0; padding: 1.2rem 1.4rem; color: var(--black);border: var(--border); font-size:1.8rem; width: 100%;" name="contact" cols="100" rows="5" placeholder="Enter a address for contacting" required></textarea>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add info" name="add_info" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_member_id = mysqli_query($conn,"SELECT `id` FROM `company`") or die ('query failed');
         if(mysqli_num_rows($select_member_id) > 0){            
            while($fetch_member= mysqli_fetch_assoc($select_member_id)){
               $pid=$fetch_member['id'];

               $fetch_member = mysqli_query($conn,"SELECT * FROM `company` WHERE id='$pid'");
               $fetch_member = mysqli_fetch_array($fetch_member);
      ?>
      <div class="box">
         <img style="max-width:100%;" src="uploaded_img/<?php echo $fetch_member['img']; ?>" alt="">
         <div class="name"><?php echo $fetch_member['name']; ?></div>
         <a href="admin_company.php?update=<?php echo $fetch_member['id']; ?>" class="option-btn">update</a>
         <a href="admin_company.php?delete=<?php echo $fetch_member['id']; ?>" class="delete-btn" onclick="return confirm('delete this member?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no members added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form" style="padding: 10px;">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `company` WHERE id = '$update_id' ") or die('query failed');
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
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter title">
      <!--get Content -->
      <textarea class="box" rows = "2" cols = "40" name = "update_contact" required><?php echo $fetch_update['contact']; ?></textarea>
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_member" class="btn">
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
   window.location.href = 'admin_company.php';
}
</script>

</body>
</html>