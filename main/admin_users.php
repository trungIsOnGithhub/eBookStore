<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delOrder=mysqli_query($conn,"SELECT * FROM `orders` WHERE ACC_ID='$delete_id'") or die ('query failed');
   if(mysqli_num_rows($delOrder)>0) {
   $delOrder=mysqli_fetch_array($delOrder);
   $delOrder=$delOrder['Order_ID'];

   mysqli_query($conn,"DELETE FROM `order_detail` WHERE ORDERID='$delOrder'") or die ('query failed');
   }

  
   mysqli_query($conn,"DELETE FROM `orders` WHERE ACC_ID='$delete_id'") or die('query failed');
   mysqli_query($conn,"DELETE FROM `discount_code` WHERE ACC_ID='$delete_id'")or die('query failed');
   mysqli_query($conn,"DELETE FROM `review` WHERE ACCID='$delete_id'")or die('query failed here');
   
   mysqli_query($conn, "DELETE FROM `account` WHERE Account_ID = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
}

if(isset($_GET['disable'])) {
   $disable_id = $_GET['disable'];
   $Deleted=mysqli_query($conn,"SELECT Deleted FROM `account` WHERE Account_ID='$disable_id'") or die ('query failed');
   $Deleted = mysqli_fetch_array($Deleted);
   $Deleted=$Deleted['Deleted'];
   if($Deleted) {
   mysqli_query($conn,"UPDATE `account` SET Deleted = '0' WHERE Account_ID='$disable_id'") or die ('query failed');
   }
   else {
      mysqli_query($conn,"UPDATE `account` SET Deleted = '1' WHERE Account_ID='$disable_id'") or die ('query failed');
   }
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <style>
      .disable-btn {
         display: inline-block;
         margin-top: 1rem;
        padding:1rem 3rem;
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

<section class="users">

   <h1 class="title"> user accounts </h1>

   <div class="box-container">
      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `Account` WHERE ROLE_NO='4'") or die('query failed');
         while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>
      <div class="box">
         <p> User id : <span><?php echo $fetch_users['Account_ID']; ?></span> </p>
         <p> First name : <span><?php echo $fetch_users['FName']; ?></span> </p>
         <p> Last name : <span><?php echo $fetch_users['LName']; ?></span> </p>
         <p> Email : <span><?php echo $fetch_users['Email']; ?></span> </p>
         <p> Start date : <span><?php echo $fetch_users['Start_date']; ?></span> </p>

         <a href="admin_users.php?delete=<?php echo $fetch_users['Account_ID']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete user</a>
         <a  href="admin_users.php?disable=<?php echo $fetch_users['Account_ID']; ?>" onclick="return confirm('change stat this user?');" class="disable-btn"><?php if($fetch_users['Deleted']==0) {
            echo "Disable";
         }
         else {
            echo "Enable";
         }
       ?></a>
          <a href="admin_users_comment.php?id=<?php echo $fetch_users['Account_ID']; ?>" class="detail-btn">View review</a>
          <a href="admin_users_order.php?id=<?php echo $fetch_users['Account_ID']; ?>" class="order-btn">View orders</a>
         </div>
      <?php
         };
      ?>
   </div>

</section>









<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>