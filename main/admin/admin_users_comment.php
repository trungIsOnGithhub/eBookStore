<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};
if(isset($_REQUEST['delete'])){
    $review_id = $_REQUEST["delete"];
    mysqli_query($conn,"DELETE FROM `review` WHERE ID='$review_id'") or die ('query failed');
    $message[]="The review has been deleted";
    }

if(isset($_REQUEST['id'])){
$user_id = $_REQUEST["id"];
$select_com=mysqli_query($conn,"SELECT * FROM `review` WHERE ACCID = '$user_id' ORDER BY Product_ID") or die ('query failed');
}
if(isset($_REQUEST['sort_by'])){ 
    $sort_by = $_REQUEST["sort_by"];
    if($sort_by=="ratelo")
    $select_com=mysqli_query($conn,"SELECT * FROM `review` WHERE ACCID = '$user_id' ORDER BY Rating asc") or die ('query failed');
    else if($sort_by=="ratehi")
    $select_com=mysqli_query($conn,"SELECT * FROM `review` WHERE ACCID = '$user_id' ORDER BY Rating desc") or die ('query failed');
    else
    $select_com=mysqli_query($conn,"SELECT * FROM `review` WHERE ACCID = '$user_id' ORDER BY Created_date desc") or die ('query failed');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Customer detail</title>

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

   <h1 class="title">Comments about products</h1>
   

</section>

<!-- product CRUD section ends -->
<table class="all-details">
<form action="admin_users_comment.php" method="GET">
    <select id="sort-by" name="sort_by">
            <!-- <option value="nosort">Non Sort</option> -->
            <option value="ratelo">Low Rating</option>
            <option value="ratehi">High Rating</option>
            <option value="New">Newest</option>
    </select>
    <select id="catego" name="id" style="display:none;">
            <option value=<?php echo $user_id;?>></option>
    </select>
<button id="sub-btn" type="submit"><i class="fas fa-filter"></i></button>
</form>
                           <thead>
                              <tr>
                                <th>Created Date</th>
                                <th>Cotent</th>
                                <th>Product Id</th>
                                <th>Rating</th>
					            <th>Action</th>
                              </tr>
                            </thead>

                            <tbody>
                                <?php 
                                    while($result_comment = $select_com->fetch_assoc()){
                                        echo'<tr>
                                        <td>' .
                                          $result_comment['Created_date'] . '
                                        </td>
                                        <td>' .
                                          $result_comment['Content'] .'
                                        </td>
                                        <td>' .
                                          $result_comment['Product_ID'] .'
                                        </td>
                                        <td>'.
                                          $result_comment['Rating'] .'
                                        </td>
                                        <td><a href="admin_users_comment.php?delete='.$result_comment['ID'].'&id='.$result_comment['ACCID'].'"class="delete-btn">delete</a></td>
                                       </tr>';
                                    }
                                ?>
                                
                            </tbody>
</table>
<?php
         if(isset($_REQUEST['sort_by'])){
            echo '<script>document.getElementById("sort-by").value="'.$_REQUEST['sort_by'].'";</script>';
         }
?>
</body>
</html>