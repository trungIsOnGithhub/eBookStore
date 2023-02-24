<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['received'])) {
   $Order_ID=$_POST['Order_ID'];

   mysqli_query($conn,"UPDATE `orders` SET Status = 'Completed' WHERE Order_ID='$Order_ID'") or die ('query failed');
   $message[]="Status updated successfully !";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ORDERS</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>your orders</h3>
   <p> <a href="home.php">HOME</a> / ORDERS </p>
</div>

<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

      <?php
         $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE ACC_ID = '$user_id' order by pay_date desc") or die('query failed');
         if(mysqli_num_rows($order_query) > 0){
            while($fetch_orders = mysqli_fetch_assoc($order_query)){
      ?>
      <div class="box">
         <p> placed on : <span><?php echo $fetch_orders['pay_date']; ?></span> </p>
         <p> address : <span><?php echo $fetch_orders['Address']; ?></span> </p>
         <p> shipping method : <span><?php $mid= $fetch_orders['METHOD_ID']; 
         $method=mysqli_query($conn,"SELECT * FROM `shipping_method` WHERE Method_ID='$mid'") or die ('query failed');
         $method=mysqli_fetch_array($method);
         echo $method['Name'];
         ?></span></p>
         <p> your orders : <span><?php 
          $order_id=$fetch_orders['Order_ID'];
          $get_product=mysqli_query($conn,"SELECT* FROM `order_detail` WHERE ORDERID='$order_id'") or die ('query failed');
          if(mysqli_num_rows($get_product)>0) {
            while($fetch_product=mysqli_fetch_assoc($get_product)) {
               $PID=$fetch_product['PID'];
               $pname=mysqli_query($conn,"SELECT* FROM `product` WHERE Product_ID='$PID' ");
               $pname=mysqli_fetch_array($pname);
               $pname=$pname['Name'];
               echo $pname."(".$fetch_product['Quantity']."), ";
            }
          }
         ?></span> </p>
         <p> total price : <span>$<?php echo $fetch_orders['Total_amount']. " + $".$method['Fee']; ?></span> </p>
         <p> payment status : <span style="color:<?php 
         if ($fetch_orders['Status']=='Processing') {echo 'rgb(255, 204, 0)'; }
         else if ($fetch_orders['Status']=='Cancelled') {echo 'red'; }
         else{ echo 'green'; } ?>;"><?php echo $fetch_orders['Status']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="Order_ID" value="<?php echo $fetch_orders['Order_ID']; ?>">
      <input type="submit" value="Received" class="btn" name="received">
         </form>
      </div>
      
      <?php
       }
      }else{
         echo '<p class="empty">No orders placed yet!</p>';
      }
      ?>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>