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

<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">NORMAL STAFF <span>PANEL</span></a>

      <nav class="navbar">
         <a href="staff_page.php">HOME</a>
         <a href="staff_orders.php">ORDER</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p>username : <span><?php echo $_SESSION['staff_name']; ?></span></p>
         <p>email : <span><?php echo $_SESSION['staff_email']; ?></span></p>
         <a href="../logout.php" class="delete-btn">logout</a>
      </div>

   </div>

</header>