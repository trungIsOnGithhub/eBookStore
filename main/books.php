<?php
   include 'config.php';
   require 'inherit/dbc.php';
   require 'inherit/data_abstract.php';
   require 'books_mvc/books_model.php';
   require 'books_mvc/books_view.php';
   require 'books_mvc/books_controller.php'; 

   session_start();

   //GLOBAL VARIABLES
   $user_id = session_check_if_not_logout($_SESSION, 'user_id');
   $main_data_container_array = array();

   //MVC model
   $main_view = new BooksView();
   $main_models = new BooksModel($user_id);
   $main_controller = new BooksController();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!--SEO tag-->
   <meta name="description" content="Visit our bookstore, shop latest and most popular books, magazines,...everything readable.">
   <meta name="description" content="Any thing you want to read is here, books, magazines, or even magazine too...">
   <meta name="description" content="A truly place for a bookworm, indulge reading, bibliophile,.... everything related to books.">
   <meta name="description" content="Looking for a preriodical, journal, issues,... here is what for you.">

   <title>BOOKS</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php
   require 'header.php';
   $main_view->display_add_to_cart_message($main_models, $main_controller);
?>

<div class="heading">
   <h3>our shop</h3>
   <p><a href="home.php">HOME</a>/BOOKS</p>
</div>

<section class="products">
   <div style="display:flex;justify-content:center;margin: 2rem 0;">
      <!--Before Modifed-->
      <h1 class="title">ALL PRODUCTS</h1>
      <!--Before Modifed-->
      <form action="books.php" method="GET">
         <select id="sort-by" name="sort_by">
            <!-- <option value="nosort">Non Sort</option> -->
            <option value="pricelo">Low Price</option>
            <option value="pricehi">High Price</option>
            <option value="discounthi">High Discount</option>
         </select>
         <select id="catego" name="catego">
            <option value="all_catego">All Category</option>

         <?php $main_view->display_categories($main_models, 3); ?>

         </select>
         <button id="sub-btn" type="submit"><i class="fas fa-filter"></i></button>
      </form>

      <?php $main_view->display_current_sort_and_category($main_controller); ?>

   </div>
   <div class="box-container">
   <?php
      $query_mode = $main_controller->is_sort_discount_high($_GET) ? 2 : 1;
      $len_fetched = $main_models->get_array_data($main_data_container_array, $query_mode, 10);

      $books_sort_price_high = $main_controller->is_sort_price_high($_GET);
      $curr_start_idx = $books_sort_price_high? ($len_fetched-1) : 0;
      $curr_end_idx = $books_sort_price_high? -1 : $len_fetched;
      $jump = $books_sort_price_high? -1 : 1;
      //4 lines above will help backwardly travel the array we got from the DB, if the sort is "highest price first"
      //or normally travel the array otherwise

      if($len_fetched > 0) {
      for($i = $curr_start_idx; $i != $curr_end_idx; $i=$i+$jump) {
         if( !$main_controller->is_current_category($_GET, 'all_catego')
               && !$main_controller->is_current_category($_GET, $main_data_container_array[$i]['catego_name']) )
                  { continue; }
         $fetch_products = $main_data_container_array[$i];
   ?>
      <form action="" method="POST" class="box">
         <img id="detailid" class="image" src="uploaded_img/<?php echo $fetch_products['thumbnail']; ?>" alt="product image" onclick="location.href='details.php?product_id=<?php echo $fetch_products['id']; ?>';">
         <p class="name"><?php echo $fetch_products['book_name']; ?></p>

         <?php $main_view->display_books_price($fetch_products);
               $main_view->display_books_stock($fetch_products, $FEW_STOCK_LIMIT); ?>

         <input type="number" min="1" name="product_quantity" value="1" class="qty">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['book_name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['discount_price'] ? $fetch_products['discount_price'] : $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['thumbnail']; ?>">
         <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>" >
         <input type="hidden" name="product_stock" value="<?php echo $fetch_products['quantity']; ?>" >

         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </form>
      <?php
         }
      }
      else { echo '<p class="empty">No Products Found!!</p>'; }
      ?>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>