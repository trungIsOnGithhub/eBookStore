<?php
    class HomePageView {
        public function display_books_price($fetch_products) {
            if( is_null($fetch_products) || !isset($fetch_products['Price']) || is_null($fetch_products['Price']) ) {
               error_trigger('display_books_price() error!!');
            }
         
            if( isset($fetch_products['Discount_price']) && !is_null($fetch_products['Discount_price']) ) {
               echo '<div class="price"><s style="text-decoration: line-through">$'.$fetch_products['Price'].'</s>';
               echo ' | $'.$fetch_products['Discount_price'].'</div>';
            }
            else
               { echo '<div class="price">$'.$fetch_products['Price'].'</div>'; }
        }

        public function display_add_to_cart_message($main_models, $main_controller) {
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
    }
?>