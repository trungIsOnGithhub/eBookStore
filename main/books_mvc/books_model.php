<?php
    class BooksModel extends DBC {
        //static properties thi truy cap bang ::(double colon)
        //non-static properties thi truy cap bang ->(arrow)
        private static $user_id; 
        private static $cached_query1;
        private static $cached_query2;
        private static $cached_query3;
        //persistent data about books need to be cache to avoid querying from DB many times

        // each query will have a mode 1,2,3,... by the order below
        private $query1, $query2, $query3;

        function __construct($_user_id) {
            self::$user_id = $_user_id;

            $this->query1 = "SELECT `product`.`Product_ID` AS id, `product`.`Discount_price` AS discount_price, `product`.`Price` AS price, `category`.`Name` AS catego_name, `product`.`Thumbnail` AS thumbnail, `product`.`Name` AS book_name, `book`.`Quantity_in_store` AS quantity FROM `book` JOIN `category` ON `book`.`CATEG_ID` = `category`.`Category_ID` JOIN `product` ON `book`.`Product_ID` = `product`.`Product_ID` where deleted=0 ORDER BY price ASC";
            $this->query2 = "SELECT `product`.`Product_ID` AS id, `product`.`Discount_price` AS discount_price, `product`.`Price` AS price, `category`.`Name` AS catego_name, `product`.`Thumbnail` AS thumbnail, `product`.`Name` AS book_name, `book`.`Quantity_in_store` AS quantity FROM `book` JOIN `category` ON `book`.`CATEG_ID` = `category`.`Category_ID` JOIN `product` ON `book`.`Product_ID` = `product`.`Product_ID` where deleted=0 ORDER BY (price-discount_price) DESC";
            $this->query3 = "SELECT `Name` FROM `category`";
        }

        protected function get_raw_data_by($mode) {
            if($mode == 1) {
                if( is_null(self::$cached_query1) ) {
                    self::$cached_query1 = $this->get_connect()->query($this->query1);
                }
                return self::$cached_query1;
                // return $this->get_connect()->query($this->query1);
            }
            if($mode == 2) {
                if( is_null(self::$cached_query2) ) {
                    self::$cached_query2 = $this->get_connect()->query($this->query2);
                }
                return self::$cached_query2;
            }
            if($mode == 3) {
                if( is_null(self::$cached_query3) ) {
                    self::$cached_query3 = $this->get_connect()->query($this->query3);
                }
                return self::$cached_query3;
            }
            return NULL;
        }

        public function get_array_data(&$record_array, $mode, $num_fetch = 0) {
            // Need to check if query contains SELECT, FROM
            if($num_fetch <= 0) {
                return 0;
            }

            $res_table = $this->get_raw_data_by($mode);
    
            // $data = array();
            while($num_fetch > 0 && $row = $res_table->fetch_assoc()) {
                $record_array[] = $row;
                $num_fetch--;
            }

            // return an index array where each element of query result row
            return count($record_array);
        }

        public function is_null_in_mode($mode) {
            return $this->check_data_null( $this->get_raw_data_by($mode) );
        }

        public function is_product_exist_in_cart($books_object) {
            $product_name = $books_object->name;

            $check_product_in_cart_query = "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '".self::$user_id."'";

            $raw_data_check_product = $this->get_connect()->query($check_product_in_cart_query);

            return $raw_data_check_product->num_rows > 0;
        }
        public function insert_into_cart($books_object) {
            $product_quantity = $books_object->quantity;
            $product_image = $books_object->image;
            $product_price = $books_object->price;
            $product_name = $books_object->name;
            $product_id = $books_object->id;

            $insert_query = "INSERT INTO `cart`(user_id,Product_ID, name, price, quantity, image) VALUES('".self::$user_id."','$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')";

            $this->get_connect()->query($insert_query);
            //may error handling goes here
        }
    }
?>