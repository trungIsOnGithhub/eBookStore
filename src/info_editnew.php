<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $Email = mysqli_real_escape_string($conn, $_POST['Email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['Password']));
   $select_users = mysqli_query($conn, "SELECT * FROM `account` WHERE Email = '$Email' AND Password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){

      $row = mysqli_fetch_assoc($select_users);
      $ROLE_NO = $row['ROLE_NO'];

      $res=mysqli_query($conn,"SELECT * FROM `role` WHERE Role_ID = '$ROLE_NO' ") or die ('query failed');

      $result=mysqli_fetch_assoc($res);

      

      if($result['Role_name'] == 'quanly'){

         $_SESSION['admin_name'] = $row['FName'].' '.$row['LName'];
         $_SESSION['admin_email'] = $row['Email'];
         $_SESSION['admin_id'] = $row['Account_ID'];
         header('location:admin_page.php');

      }

      elseif($result['Role_name']=='nhanvienthuong') {
         if($row['Deleted']==0) {
         $_SESSION['staff_name'] = $row['FName'].' '.$row['LName'];
         $_SESSION['staff_email'] = $row['Email'];
         $_SESSION['staff_id'] = $row['Account_ID'];
         header('location:normal_staff/staff_page.php');
         }
         else {
            $message[]="Your account has been disabled by the Admin";
         }
      }

      elseif ($result['Role_name']=='nhanvienkho') {
         if ($row['Deleted']==0) {         
         $_SESSION['staff_name'] = $row['FName'].' '.$row['LName'];
         $_SESSION['staff_email'] = $row['Email'];
         $_SESSION['staff_id'] = $row['Account_ID'];
         header('location:storage_staff/staff_page.php');
         }

         else {
            $message[]="Your account has been disabled by the Admin";
         }
      }
      
      elseif($result['Role_name'] == 'khachhang'){
         if($row['Deleted']==0) {

         $_SESSION['user_name'] = $row['FName'].' '.$row['LName'];
         $_SESSION['user_email'] = $row['Email'];
         $_SESSION['user_id'] = $row['Account_ID'];
         header('location:home.php');
         }
         else {
            $message[]="Your account has been disabled by the Admin";
         }

      }

   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> -->

   <!-- <link rel="stylesheet" href="css/style.css"> -->
   

   <link rel="stylesheet" href="css/login.css">


</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?> 
   
<div class="container" id="container">
  <div class="form-container sign-in-container">
    <form action="" method="post">
      <h1>Sign in</h1>
     
      <input type="email" name="Email" placeholder="Email" required />
      <input type="password" name="Password" placeholder="Password" required/>
      <input type="submit" name="submit" value="login now" class="btn">
      <button class="ghost" id="signUp1"> <a href="registerr.php">Sign Up</a></button>
    </form>
  </div>
</div>
</body>
</html>