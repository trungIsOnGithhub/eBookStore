<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_staff'])){

    $FName = mysqli_real_escape_string($conn, $_POST['FName']);
    $LName = mysqli_real_escape_string($conn, $_POST['LName']);
    $TelephoneNum = mysqli_real_escape_string($conn, $_POST['TelephoneNum']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $Address = mysqli_real_escape_string($conn, $_POST['Address']);
 
    $Password = mysqli_real_escape_string($conn, md5($_POST['Password']));
    $Role=$_POST['Role'];
 
    $select_users = mysqli_query($conn, "SELECT * FROM `account` WHERE Email = '$Email'") or die('query failed');

    
    
    if(mysqli_num_rows($select_users) > 0){
        $message[] = 'user already exist!';
     }else{       
    
           $res = mysqli_query($conn,"SELECT `Role_ID` FROM `role` WHERE Role_name='$Role';");
           $result = mysqli_fetch_array($res);
           $RoleID = $result['Role_ID'];
  
  
           mysqli_query($conn, "INSERT INTO `account`(FName, LName, TelephoneNum,Email,Address,Password,ROLE_NO) VALUES('$FName','$LName','$TelephoneNum', '$Email','$Address', '$Password','$RoleID')") or die('query failed');
  
           $message[] = 'add staff successfully!';
        
     }   
}

if(isset($_POST['update_staff'])){

    $update_p_id = $_POST['update_p_id'];
    $update_FName = $_POST['update_FName'];
    $update_LName = $_POST['update_LName'];
    $update_TelephoneNum=$_POST['update_TelephoneNum'];
    $update_Address=$_POST['update_Address'];
    $update_Email=$_POST['update_Email'];
    $update_Password=$_POST['update_Password'];
    $update_Role=$_POST['update_Role'];

    $s1=0;
    $s2=0;

    $check=mysqli_query($conn,"SELECT Account_ID FROM `account` WHERE Email='$update_Email'");
    if(mysqli_num_rows($check)>0) {
      $s1=1;
    }

    $check=mysqli_fetch_array($check);
    $check=$check['Account_ID'];
    if ($check!=$update_p_id) {
      $s2=1;
    }

    if($s1&&$s2) {
      $message[]="Email Existed, Please Change !";
    }



    else {
    
    if($update_Password) {
      $update_Password=md5($update_Password);
    mysqli_query($conn, "UPDATE `account` SET FName='$update_FName', LName='$update_LName', TelephoneNum='$update_TelephoneNum', Address='$update_Address', Email='$update_Email', Password='$update_Password', ROLE_NO='$update_Role' WHERE Account_ID='$update_p_id'"
    ) or die ('query failed here');
    }

    else {
      mysqli_query($conn, "UPDATE `account` SET FName='$update_FName', LName='$update_LName', TelephoneNum='$update_TelephoneNum', Address='$update_Address', Email='$update_Email', ROLE_NO='$update_Role' WHERE Account_ID='$update_p_id'"
    ) or die ('query failed');
    
    }

    
 
 
    header('location:admin_staff.php'); 

   }
 }

 if(isset($_GET['disable'])) {
   $id=$_GET['disable'];
   $get_staff=mysqli_query($conn,"SELECT * FROM `account` WHERE Account_ID='$id'") or die('query failed');
   $get_staff=mysqli_fetch_array($get_staff);
   $checkDel=$get_staff['Deleted'];

   if($checkDel){
   mysqli_query($conn,"UPDATE `account` SET Deleted=0 WHERE Account_ID='$id'") or die ('query failed');
   $message[]="Staff ".$id." has been enabled";
   }

   else {
      mysqli_query($conn,"UPDATE `account` SET Deleted=1 WHERE Account_ID='$id'") or die ('query failed');
      $message[]="Staff ".$id." has been disabled";
   }
   
 }


 if(isset($_GET['delete'])) {
   $id=$_GET['delete'];
   

   
   mysqli_query($conn,"DELETE FROM `account` WHERE Account_ID='$id'") or die ('query failed');
   $message[]="Staff ".$id." has been enabled";
   
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>all magazines</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <style>
      .disable-btn {
         display: inline-block;
         margin-top: 1rem;
        padding:8px;
        cursor: pointer;
        color:var(--black);
        font-size: 1.8rem;
       border-radius: .5rem;
       text-transform: capitalize;
       background-color: yellow;
      }
      .disable-btn:hover {
         background-color: black;
         color: white;
      }

   </style>

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">All staff</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add staff</h3>
      <input style="width: 50%; float: left;" type="text" name="FName" class="box" placeholder="First name" required>
      <input style="width: 50%; float: left;" type="text" name="LName" class="box" placeholder="Last name" required>
      <input type="text" name="TelephoneNum" class="box" placeholder="Phone number" required>
      <input type="text" name="Address" class="box" placeholder="Address" required>

      <input type="text" name="Email" class="box" placeholder="Email" required>
      <input type="password" name="Password" class="box" placeholder="Password" required>

      <select name="Role" class="box">
      <option value="" selected disabled hidden>Role</option>
         <option value="nhanvienthuong">Normal Staff</option>
         <option value="nhanvienkho">Storage Staff</option>
      </select>

      <input type="submit" value="add staff" name="add_staff" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->
<h2 class="title">Normal Staff</h2>
<section class="show-products">

   <div class="box-container">

      <?php
          $select_staff = mysqli_query($conn,"SELECT * FROM `account` WHERE ROLE_NO='2'") or die ('query failed');
          if(mysqli_num_rows($select_staff) > 0){            
             while($fetch_staff= mysqli_fetch_assoc($select_staff)){
                
      ?>
      <div class="box">
         <div class="name"><?php echo $fetch_staff['FName']. " ".$fetch_staff['LName']; ?></div>
         <div class="name">Normal Staff</div>
         <div class="price">
            <?php
            if($fetch_staff['Deleted']=='0') {
                echo 'Available';
            }
            else {
                echo 'Disabled';
            }
            ?>

         </div>
         <a href="admin_staff.php?update=<?php echo $fetch_staff['Account_ID']; ?>" class="option-btn">update</a>
         <a href="admin_staff.php?disable=<?php echo $fetch_staff['Account_ID']; ?>" class="disable-btn"><?php
         if($fetch_staff['Deleted']==1) {
            echo "enable";
         }
         else {
            echo "disable";
         }
          ?></a>
         <a href="admin_staff.php?delete=<?php echo $fetch_staff['Account_ID']; ?>" class="delete-btn" onclick="return confirm('delete this employee?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no staff!</p>';
      }
      ?>
   </div>

</section>
<h2 class="title">Storage Staff</h2>
<section class="show-products">

   <div class="box-container">

      <?php
          $select_staff = mysqli_query($conn,"SELECT * FROM `account` WHERE ROLE_NO='3'") or die ('query failed');
          if(mysqli_num_rows($select_staff) > 0){            
             while($fetch_staff= mysqli_fetch_assoc($select_staff)){
                
      ?>
      <div class="box">
         <div class="name"><?php echo $fetch_staff['FName']. " ".$fetch_staff['LName']; ?></div>
         <div class="name">Storage Staff</div>
         <div class="price">
            <?php
            if($fetch_staff['Deleted']=='0') {
                echo 'Available';
            }
            else {
                echo 'Disabled';
            }
            ?>

         </div>
         <a href="admin_staff.php?update=<?php echo $fetch_staff['Account_ID']; ?>" class="option-btn">update</a>
         <a href="admin_staff.php?disable=<?php echo $fetch_staff['Account_ID']; ?>" class="disable-btn"><?php
         if($fetch_staff['Deleted']==1) {
            echo "enable";
         }
         else {
            echo "disable";
         }
          ?></a>
         <a href="admin_staff.php?delete=<?php echo $fetch_staff['Account_ID']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no staff!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `account` WHERE Account_ID = '$update_id' ") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <!-- Get account id-->
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['Account_ID']; ?>">

      <!--get Name -->
      <input style="width: 50%; float: left;" type="text" name="update_FName" value="<?php echo $fetch_update['FName']; ?>" class="box" required placeholder="enter first name">

      <input style="width: 50%; float: left;" type="text" name="update_LName" value="<?php echo $fetch_update['LName']; ?>" class="box" required placeholder="enter last name">

      <input type="text" name="update_TelephoneNum" value="<?php echo $fetch_update['TelephoneNum']; ?>" class="box" required placeholder="enter telephone">
      <!-- Address -->
      <input type="text" name="update_Address" value="<?php echo $fetch_update['Address']; ?>" class="box" required placeholder="enter address">


      <input type="text" name="update_Email" value="<?php echo $fetch_update['Email']; ?>" class="box" required placeholder="enter email">

      <input type="password" name="update_Password" class="box" placeholder="enter new password">

      <select name="update_Role" class="box">
        <?php
        if($fetch_update['ROLE_NO']==2) {
            echo "
         <option value='2' selected>Normal Staff</option>
         <option value='3'>Storage Staff</option>
         ";
        }

        else {
            echo "
            <option value='2' >Normal Staff</option>
            <option value='3'selected>Storage Staff</option>
            ";
        }

        ?>
      </select>

   
      <input type="submit" value="update" name="update_staff" class="btn">
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
   window.location.href = 'admin_staff.php';
}
</script>
</script>
</body>
</html>