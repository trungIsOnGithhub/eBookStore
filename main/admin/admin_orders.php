<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_Status = $_POST['update_Status'];
   mysqli_query($conn, "UPDATE `orders` SET Status = '$update_Status' WHERE Order_ID = '$order_update_id'") or die('query failed');
   $message[] = 'payment status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `order_detail` WHERE ORDERID = '$delete_id'") or die('query failed here');
   mysqli_query($conn, "DELETE FROM `orders` WHERE Order_ID = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}
$select_order=mysqli_query($conn,"SELECT * FROM `orders` ORDER BY pay_date desc") or die ('query failed');
if(isset($_REQUEST['sort_by'])){
   $sort_by = $_REQUEST["sort_by"];
    if($sort_by=="processing")
    $select_order=mysqli_query($conn,"SELECT * FROM `orders` WHERE Status = 'Processing' ORDER BY pay_date desc") or die ('query failed');
    else if($sort_by=="delivering")
    $select_order=mysqli_query($conn,"SELECT * FROM `orders` WHERE Status = 'Delivering' ORDER BY pay_date desc") or die ('query failed');
    else if($sort_by=="completed")
    $select_order=mysqli_query($conn,"SELECT * FROM `orders` WHERE Status = 'Completed' ORDER BY pay_date desc") or die ('query failed');
    else if($sort_by=="canceled")
    $select_order=mysqli_query($conn,"SELECT * FROM `orders` WHERE Status = 'Cancelled' ORDER BY pay_date desc") or die ('query failed');
    else
    $select_order=mysqli_query($conn,"SELECT * FROM `orders` ORDER BY pay_date desc") or die ('query failed');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">

   <h1 class="title">placed orders</h1> 
   <form style="text-align:right;"action="admin_orders.php" method="GET">
    <select id="sort-by" name="sort_by">
            <option value="all">All</option>
            <option value="processing">Processing</option>
            <option value="delivering">Delivering</option>
            <option value="completed">Completed</option>
            <option value="canceled">Canceled</option>
    </select>
<button id="sub-btn" type="submit"><i class="fas fa-filter"></i></button>
</form>

   <div class="box-container">
      <?php
      if(mysqli_num_rows($select_order) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_order)){
      ?>
      <div class="box">
         <p> User id : <span><?php echo $fetch_orders['ACC_ID']; ?></span> </p>
         <p> Placed on : <span><?php echo $fetch_orders['pay_date']; ?></span> </p>
         <p> Name : <span><?php 
         $Account_ID=$fetch_orders['ACC_ID'];
         $get_account=mysqli_query($conn,"SELECT* FROM `account` WHERE Account_ID='$Account_ID'");
         $get_account=mysqli_fetch_array($get_account);
         $name=$get_account['FName']." ".$get_account['LName'];
         echo $name;
          ?></span> </p>
         <p> Email : <span><?php echo $get_account['Email']; ?></span> </p>
         <p> Address : <span><?php echo $get_account['Address']; ?></span> </p>
         <p> Total products : <span><?php 
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
         <p> Shipping method : <span><?php 
         $mid= $fetch_orders['METHOD_ID']; 
         $method=mysqli_query($conn,"SELECT * FROM `shipping_method` WHERE Method_ID='$mid'") or die ('query failed');
         $method=mysqli_fetch_array($method);
         echo $method['Name'];
          ?></span> </p>
         <p> Total price : <span>$<?php echo $fetch_orders['Total_amount']. " + $".$method['Fee']; ?></span> </p>        
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['Order_ID']; ?>">
            <select name="update_Status">
               <option value="" selected disabled><?php echo $fetch_orders['Status']; ?></option>
               <option value="Processing">Processing</option>
               <option value="Delivering">Delivering</option>
               <option value="Cancelled">Cancelled</option>
            </select>
            <input type="submit" value="update" name="update_order" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['Order_ID']; ?>" onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>
   </div>

</section>










<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>
<?php
         if(isset($_REQUEST['sort_by'])){
            echo '<script>document.getElementById("sort-by").value="'.$_REQUEST['sort_by'].'";</script>';
         }
?>
</body>
</html>