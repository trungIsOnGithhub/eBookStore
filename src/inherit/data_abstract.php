<?php
    class Books {
        public $name, $price, $image, $quantity, $id, $stock;

        function __construct($_name, $_price, $_image, $_quantity, $_id, $_stock) {
            $this->name = $_name;
            $this->price = $_price;
            $this->image = $_image;
            $this->quantity = $_quantity;
            $this->id = $_id;
            $this->stock = $_stock;
        }
    }
?>