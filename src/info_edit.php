<?php

include 'config.php';
session_start();

if(array_key_exists('aload',$_GET) && $_GET['aload']) {
   $message[] = "Updated info successfully, you may close this notification now!";
   $_GET['aload'] = 0;
   unset($_GET['aload']);
}

$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}
$info = mysqli_query($conn,"SELECT * FROM `account` WHERE Account_ID='$user_id'") or die ('query failedd');
$info= mysqli_fetch_array($info);

if(isset($_POST['submit'])){
    $FName = mysqli_real_escape_string($conn, $_POST['FName']);
    $LName = mysqli_real_escape_string($conn, $_POST['LName']);
    $TelephoneNum = mysqli_real_escape_string($conn, $_POST['TelephoneNum']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $Address = mysqli_real_escape_string($conn, $_POST['Address']);
    $Rpass=$_POST['password']; 
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
 
    $check_FName=1;
    $check_LName=1;
    $check_TelephoneNum=1;
    $check_Password=1;

    $len=strlen($FName);
    for ($i=0; $i<$len; $i++) {
       if(($FName[$i]>='a'&&$FName[$i]<='z')||($FName[$i]>='A'&&$FName[$i]<='Z')) {
          continue;
       }
       else {
          $check_FName=0;
       }
    }
 
    $len=strlen($LName);
    for ($i=0; $i<$len; $i++) {
       if(($LName[$i]>='a'&&$LName[$i]<='z')||($LName[$i]>='A'&&$LName[$i]<='Z')) {
          continue;
       }
       else {
          $check_LName=0;
       }
    }
 
    $len=strlen($TelephoneNum);
    if($TelephoneNum[0]!='0') {
       $check_TelephoneNum=0;
    }
    for ($i=0; $i<$len; $i++) {
       if(($TelephoneNum[$i]>='0'&&$TelephoneNum[$i]<='9')) {
          continue;
       }
       else {
          $check_TelephoneNum=0;
       }
    }
 
 
    $len=strlen($Rpass);
    if($len<8 and $len>0) {
       $check_Password=0;
    }
    for ($i=0; $i<$len; $i++) {
       if(($Rpass[$i]>='0'&&$Rpass[$i]<='9')||($Rpass[$i]>='a'&&$Rpass[$i]<='z')||($Rpass[$i]>='A'&&$Rpass[$i]<='Z')) {
          continue;
       }
       else {
          $check_Password=0;
       }
    }
    //check image - Trung - 12/12/2022
    $check_Img = 1;
    $has_Img = 1;
    if(array_key_exists('image',$_FILES)) {
      $img = $_FILES['image']['name'];
      $img_size = $_FILES['image']['size'];   
      $img_tmp_name = $_FILES['image']['tmp_name'];
      $img_folder = 'uploaded_img/'.$img;
      if($img_size>50000000) {$check_Img = 0;}
    }
    else{$has_Img = 0;}

    //check image - Trung - 12/12/2022
   if($check_FName&&$check_LName&&$check_Password&&$check_TelephoneNum&&$check_Img&&$has_Img){
 
 
    $select_users = mysqli_query($conn, "SELECT * FROM `account` WHERE Email = '$Email' and Account_ID != '$user_id'") or die('query failed');
 
    if(mysqli_num_rows($select_users) > 0){
         $message[] = 'Email already exist!';
      }else{
         if($pass != $cpass){
            $message[] = 'Confirm password not matched!';
         }else{
            if(strlen($Rpass)==0 && strlen($img)==0){
               $account_query=mysqli_query($conn,"UPDATE `account` SET FName='$FName', LName='$LName', TelephoneNum='$TelephoneNum', Email='$Email', Address ='$Address'  WHERE Account_ID='$user_id'") or die('query failed');
            }
            else if(strlen($Rpass)==0 && strlen($img)>0){
               $account_query=mysqli_query($conn,"UPDATE `account` SET FName='$FName', LName='$LName', TelephoneNum='$TelephoneNum', Email='$Email', Address ='$Address', Image = '$img'  WHERE Account_ID='$user_id'") or die('query failed');
            }
            else if(strlen($Rpass)>0 && strlen($img)>0){
               $account_query=mysqli_query($conn,"UPDATE `account` SET FName='$FName', LName='$LName', TelephoneNum='$TelephoneNum', Email='$Email', Address ='$Address', Password='$pass', Image = '$img'  WHERE Account_ID='$user_id'") or die('query failed');
            }
            else {
               $account_query=mysqli_query($conn,"UPDATE `account` SET FName='$FName', LName='$LName', TelephoneNum='$TelephoneNum', Email='$Email', Address ='$Address', Password='$pass' WHERE Account_ID='$user_id'") or die('query failed');
            }
            if($account_query && strlen($img)>0){
               move_uploaded_file($img_tmp_name, $img_folder);
               header('location:info_edit.php?aload=1');
            }
            else if($account_query){
               $message[]="Info updated without file!";
            }
            else {
               $message[]="Update fail!";
            }
         }
      }
   }
 
 else {
    if(!$check_FName) {
       $message[]="First name is invalid";
    }
 
    if(!$check_LName) {
       $message[]="Last name is invalid";
    }
 
    if(!$check_Password) {
       $message[]="Password is too short or not valid";
    }
 
    if(!$check_TelephoneNum) {
       $message[]="Telephone number is invalid";
    }

    if(!$check_Img) {
      $message[]="File size should smaller than 5MB!";
   }
 }
 
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Info</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'header.php'; ?>
<?php
   if(array_key_exists("Image",$info) && $info["Image"]) echo '<div class="pers-img"><p>profile picture:</p><img src="'.$image_foldr.$info["Image"].'"></div>';
   else echo '<div class="pers-img"><p>profile picture:</p><i>no image</i></div>';
?>
<style>.pers-img{display:flex;justify-content:center;align-content:center;height:22rem;width:100%;background-color:#f5f5f5;}.pers-img>img{;margin-top:2rem;border:2px solid black;border-radius:0.5rem;}.pers-img>p,.pers-img>i{;margin-top:2rem;text-align:center;padding-top:8rem;padding-right:5rem;font-size:2rem;}</style>
<div class="form-container">
   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Update account info</h3>
      <input type="text" value="<?php echo $info['FName'];?>" name="FName" placeholder="<?php echo $info['FName'];?>" required class="box">
      <input type="text" value="<?php echo $info['LName'];?>" name="LName" placeholder="<?php echo $info['LName'];?>" required class="box">
      <input type="text" value="<?php echo $info['TelephoneNum'];?>" name="TelephoneNum" placeholder="<?php echo $info['TelephoneNum'];?>" required class="box">
      <input type="text" value="<?php echo $info['Address'];?>" name="Address" placeholder="<?php echo $info['Address'];?>"
      required class="box">
      <input type="text" value="<?php echo $info['Email'];?>" name="Email" placeholder="<?php echo $info['Email'];?>" required class="box">     
      <input type="password" name="password" placeholder="enter new password" class="box">
      <input type="password" name="cpassword" placeholder="confirm password" class="box">
      <p style="text-align:left;font-size:small;padding:0;margin:0;">*choose your profile picture below</p>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
      <input type="submit" name="submit" value="change info" class="infobtn">
   </form>

</div>
<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>