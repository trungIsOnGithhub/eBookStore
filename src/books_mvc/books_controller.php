<?php
    class BooksController {
        //we dont need to statically parameterize name, for readability
        public function get_current_category($get_global) {
            if( !isset($get_global['catego']) || is_null($get_global['catego']) ) {
                return 'all_catego';
            }
            return $get_global['catego'];
        }
        public function get_current_sort($get_global) {
            if( !isset($get_global['sort_by']) || is_null($get_global['sort_by']) ) {
                return 'pricelo';
            }
            return $get_global['sort_by'];
        }

        public function is_sort_price_high($get_global) {
            return strcmp($this->get_current_sort($get_global), 'pricehi') == 0;
        }
        public function is_sort_discount_high($get_global) {
            return strcmp($this->get_current_sort($get_global), 'discounthi') == 0;
        }
        public function is_sort_price_low($get_global) {
            return strcmp($this->get_current_sort($get_global), 'pricelo') == 0;
        }

        public function is_current_category($get_global, $category_type) {
            if( strcmp(gettype($category_type), 'string') != 0 ) {
                error_trigger('check current category type error!!!');
            }
            return strcmp($this->get_current_category($get_global), $category_type) == 0;
        }

        public function get_add_to_cart_info($post_global) {
            if( isset($post_global['add_to_cart']) ) {
                $name = $post_global['product_name'];
                $price = $post_global['product_price'];
                $image = $post_global['product_image'];
                $quantity = $post_global['product_quantity'];
                $id = $post_global['product_id'];
                $stock = $post_global['product_stock']-1;

                return new Books($name, $price, $image, $quantity, $id, $stock);
            }

            return NULL;
        }
    }
?>