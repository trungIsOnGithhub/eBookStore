<?php
    class HomePageController {
        //we dont need to statically parameterize name, for readability
        public function get_add_to_cart_info($post_global) {
            if( isset($post_global['add_to_cart']) ) {
                $name = $post_global['product_name'];
                $price = $post_global['product_price'];
                $image = $post_global['product_image'];
                $quantity = $post_global['product_quantity'];
                $id = $post_global['product_id'];
                //the product show on homepage are all in stock so we
                //dont need to check, just add dummy number
                $stock = 69;

                return new Books($name, $price, $image, $quantity, $id, $stock);
            }

            return NULL;
        }
    }
?>