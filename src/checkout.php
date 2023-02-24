<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['order_btn'])){

   
   $shipping_method = $_POST['shipping_method'];
   $address = $_POST['Address'];
   $note=$_POST['Note'];
   $Total_amount=0;
   $Discount = $_POST['voucher'];
   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed 1');
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
        
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $Total_amount += $sub_total;
      }
      if($Discount!=0){
         $Discount_val= mysqli_query($conn, "SELECT Discount FROM `discount_code` WHERE Code_ID = '$Discount'") or die('query failed 2');
         $Discount_val= mysqli_fetch_array($Discount_val);
         $Value= $Discount_val['Discount'];
         $Total_amount = $Total_amount * (1-$Value);}
   }

   if($Total_amount == 0){
      $message[] = 'Your cart is empty';
   }else{      
      $check_ok=1;
         if($Discount==0)
         {
            mysqli_query($conn, "INSERT INTO `orders`(Status,Total_amount,Address,ACC_ID,METHOD_ID,Note) VALUES('Processing','$Total_amount','$address','$user_id','$shipping_method', '$note')") or die('query failed here');
         }
         else{
            mysqli_query($conn, "INSERT INTO `orders`(Status,Total_amount,Address,ACC_ID,METHOD_ID,Note,CODE_ID) VALUES('Processing','$Total_amount','$address','$user_id','$shipping_method', '$note','$Discount')") or die('query failed here');
         }
         $ORDERID=mysqli_query($conn,"SELECT Order_ID FROM `orders` ORDER BY Order_ID DESC LIMIT 1");
         $ORDERID=mysqli_fetch_array($ORDERID);
         $Order_ID=$ORDERID['Order_ID'];

         $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed 3');
         while($fetch_product=mysqli_fetch_assoc($cart_query)) {
            $Price=$fetch_product['price'];
            $Quantity=$fetch_product['quantity'];
            $Total_cost=$Price*$Quantity;
            $ORDERID=$Order_ID;
            $PID=$fetch_product['Product_ID'];           

            $get_p=mysqli_query($conn,"SELECT * FROM `book` WHERE Product_ID='$PID'") or die ('query failed 4');
            if(mysqli_num_rows($get_p)>0) {
               $get_p=mysqli_fetch_array($get_p);
               $get_p_quantity=$get_p['Quantity_in_store'];

               $update_quantity=$get_p_quantity-$Quantity;
               if($update_quantity>0) {
                  mysqli_query($conn,"UPDATE `book` SET Quantity_in_store='$update_quantity' WHERE Product_ID='$PID'") or die('query failed 5');
                  mysqli_query($conn,"INSERT INTO `order_detail` (Quantity,ORDERID,PID) VALUES ('$Quantity','$ORDERID','$PID') ") or die ('query failed 6');
                  
                  
               }

               else {
                  
                  $message[]=$fetch_product['name']." is OUT OF STOCK !!!";  
                  $check_ok=0;                
               }
            }

            else {
               mysqli_query($conn,"INSERT INTO `order_detail` (Quantity,ORDERID,PID) VALUES ('$Quantity','$ORDERID','$PID') ") or die ('query failed');                
               
            }            
         }

         if($check_ok) {
            $message[] = 'order placed successfully!';
            mysqli_query($conn, "UPDATE `discount_code` SET Deleted = 1 WHERE Code_ID = '$Discount'") or die ('query failed 7');
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed 8');
         }

         else {
            mysqli_query($conn,"DELETE FROM `order_detail` WHERE ORDERID='$Order_ID'") or die ('query failed 9');
            mysqli_query($conn,"DELETE FROM `orders` WHERE Order_ID='$Order_ID'") or die ('query failed 10');
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
   <title>CHECKOUT</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

   <!-- jQuery library -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   <!-- Popper JS -->
   <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> -->
   <!-- Latest compiled JavaScript -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">HOME</a> / CHECKOUT </p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed 11');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo '$'.$fetch_cart['price'].' '.' x '. $fetch_cart['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   <input style="display:none;" type="number" id="grand-total1" value="<?php echo $grand_total; ?>" style="width: 90px; border-radius: 0px"/>
   <span style="display:flex; margin-top: 2rem; font-size: 2.5rem; color:var(--light-color); justify-content:center;">Total: $<input style="display:inline-block;color:var(--red); width:8%; background-color:transparent;" type="number" id="grand-total2" value="<?php echo $grand_total; ?>" disabled/></span>
</section>

<section class="checkout">

   <form action="" method="post">
      <h3>place your order</h3>
      <div class="flex">
      <div class="inputBox">
            <span>Voucher :</span>
            <select name="voucher" id="discount" required>
               <option value="0" data-discount="0">No voucher added</option>
               <?php
               $vourcher=mysqli_query($conn,"SELECT * FROM `discount_code` where ACC_ID=$user_id AND DATEDIFF(Expiration_date,CURDATE()) >= 0 AND Deleted=0");
               if(mysqli_num_rows($vourcher)>0) {
                  while($fetch_method=mysqli_fetch_assoc($vourcher)) {
               ?>
               <option value="<?php echo $fetch_method['Code_ID'];?>" data-discount="<?php echo $fetch_method['Discount']; ?>"><?php echo $fetch_method['Name']." | Exp-Date: ".$fetch_method['Expiration_date']; ?></option>
               <?php
                  }
               }
               ?>
            </select>
         </div>

         <div class="inputBox">
            <span>Payment Method :</span>
            <select name="pay_method">
               <option value="cod">COD-Cash On Delivery</option>
               <option value="momo">MoMo Wallet</option>
               <option value="paypal">Paypal Wallet</option>
            </select>
         </div>
        
         <div class="inputBox">
            <span>Shipping Method :</span>
            <select name="shipping_method">
               <?php
               $method=mysqli_query($conn,"SELECT * FROM `shipping_method`");
               if(mysqli_num_rows($method)>0) {
                  while($fetch_method=mysqli_fetch_assoc($method)) {
               ?>
               <option value="<?php echo $fetch_method['Method_ID']; ?>" data-shipping="<?php echo $fetch_method['Fee']; ?>"><?php echo $fetch_method['Name']." | $".$fetch_method['Fee']; ?></option>
               <?php
                  }
               }
               ?>
            </select>
         </div>

         <div class="inputBox">
            <span>Shipping Address :</span>
            <input type="text" name="Address" required placeholder="Enter your delivery address" value="<?php
             $Address= mysqli_query($conn,"SELECT Address FROM account WHERE Account_ID='$user_id'");
             $Address=mysqli_fetch_array($Address);
             echo $Address['Address'];?>">
         </div>
         
         <div class="inputBox">
            <span>Your Note :</span>
            <input type="text" name="Note" placeholder="Please enter your note">
         </div>
      </div>
      <input type="submit" value="order now" class="btn" name="order_btn">
   </form>
</section>


<script type="text/javascript">
   $(document).ready(function() {
      $("#discount").change(function () {
         var cntrol = $(this);
         var delta = cntrol.find(':selected').data('discount');

         num = parseInt( $('#grand-total1').val() );
         num *= (1-delta);

         $('#grand-total2').val( num.toFixed(0) );
      });
   });
</script>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>