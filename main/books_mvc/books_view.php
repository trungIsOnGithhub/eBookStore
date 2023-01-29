<?php
    class BooksView {
        function display_categories($data_model, $this_mode) {
            if( $data_model->is_null_in_mode($this_mode) ) {
               echo '<option value="no_catego">No category</option>';
            }
            else {
               $temp_data_categories = array();
               $data_model->get_array_data($temp_data_categories, $this_mode, 10);
         
               foreach($temp_data_categories as $category){
                  echo '<option value="'.$category['Name'].'">'.$category['Name'].'</option>';
               }
            }
        }
         
        function display_books_price($fetch_products) {
            if( is_null($fetch_products) || !isset($fetch_products['price']) || is_null($fetch_products['price']) ) {
               error_trigger('display_books_price() error!!');
            }
         
            if( isset($fetch_products['discount_price']) && !is_null($fetch_products['discount_price']) ) {
               echo '<div class="price"><s style="text-decoration: line-through">$'.$fetch_products['price'].'</s>';
               echo ' | $'.$fetch_products['discount_price'].'</div>';
            }
            else
               { echo '<div class="price">$'.$fetch_products['price'].'</div>'; }
        }
         
        function display_books_stock($fetch_products, $FEW_STOCK_LIMIT) {
            if( is_null($fetch_products) || !isset($fetch_products['quantity']) || is_null($fetch_products['quantity']) ) {
               error_trigger('display_books_stock() error!!');
            }
         
            if($fetch_products['quantity'] < $FEW_STOCK_LIMIT)
               { echo '<div id="fewleft">few in stock!</div>'; }
            else
               { echo '<div id="fewleft">In stock: '.$fetch_products['quantity'].'</div>'; }
        }
         
        function display_add_to_cart_message($main_models, $main_controller) {
            $books_to_add_info = $main_controller->get_add_to_cart_info($_POST);
         
            //No cart info to be display
            if( !isset($books_to_add_info) || is_null($books_to_add_info) ) {
               return;
            }
         
            //Invalid purchasing amount
            $books_quantity = $books_to_add_info->quantity;
            $books_stock = $books_to_add_info->stock;
            $books_name = $books_to_add_info->name;
            if( $books_quantity < 0 || $books_quantity > $books_stock ) {
               $message[] = 'Add to cart failed, only have '.$books_stock.' books for "'.$books_name.'" in stock.';
               return;
            }
         
            //check if item already exist in cart, if not, add to cart and proceed
            if( !$main_models->is_product_exist_in_cart($books_to_add_info) ) {
               $main_models->insert_into_cart($books_to_add_info);
            }
            $message[] = 'already added to cart successfully!';
        }

        function display_current_sort_and_category($main_controller) {
            echo '<script>document.getElementById("sort-by").value="'.$main_controller->get_current_sort($_GET)
                    .'";document.getElementById("catego").value="'.$main_controller->get_current_category($_GET).'";</script>';
        }
    }
?>