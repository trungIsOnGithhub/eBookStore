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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- <link rel="stylesheet" href="css/style.css"> -->
   

   <link rel="stylesheet" href="css/login.css">


</head>
<body>

<?php
// if(isset($message)){
//    foreach($message as $message){
//       echo '
//       <div class="message">
//          <span>'.$message.'</span>
//          <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
//       </div>
//       ';
//    }
// }
?>
<?php
if(isset($message)){
      echo '
      <div class="message">
         <strong>'.$message[count($message)-1].'</strong>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
}
?>

<style>
.message{
  position: sticky;
  top:0;
  margin:0 auto;
  max-width: 1200px;
  /* background-color: var(--white); */
  border-radius:10px;
  padding:2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  z-index: 10000;
  gap:1.5rem;
  background-color:#EEEEEE;
}
.message strong{
  font-size: 1rem;
  color:red;
  font-weight:;
}
.message i{
  cursor: pointer;
  color:red;
  font-size: 2rem;
}
.message i:hover{
  transform: rotate(90deg);
}
</style>

   
<!-- <div class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <input type="email" name="Email" placeholder="enter your email" required class="box">
      <input type="password" name="Password" placeholder="enter your password" required class="box">
      <input type="submit" name="submit" value="login now" class="btn">
      <p>don't have an account? <a href="register.php">register now</a></p>
   </form>

</div> -->
<div class="container" id="container">
  <div class="form-container sign-in-container">
    <form action="" method="post">
      <h1>Sign in</h1>
     
      <input type="email" name="Email" placeholder="Email" required />
      <input id="passw" type="password" name="Password" placeholder="Password" required/>
      <!--show/hide password-->
      <i class="fas fa-eye" id="toggle-passw"></i>
      <style>#toggle-passw{position:relative; left:40%;bottom:7%; cursor:pointer; z-index:69;}</style>
      <script>
      document.getElementById('toggle-passw').addEventListener('click', function(eve) {
         const password_field = document.getElementById('passw');
         const type = password_field.getAttribute('type') === 'password' ? 'text' : 'password';
         password_field.setAttribute('type', type);
         this.classList.toggle('fa-eye-slash');
      });
      </script>
      <!--show/hide password-->
      <input type="submit" name="submit" value="login now" class="btn">
      <button class="ghost" id="signUp1"> <a href="registerr.php">Sign Up</a></button>
    </form>
  </div>
  <div class="overlay-container">
    <div class="overlay">
      <div class="overlay-panel overlay-right">
        <h1>Hello, Friend!</h1>
        <p>You will need an account to start shopping with us!</p>
        <p>You don't have an account?, Sign Up below!</p>
        <button class="ghost" id="signUp"> <a href="registerr.php">Sign Up</a></button>
      </div>
    </div>
  </div>
</div>
</body>
</html>