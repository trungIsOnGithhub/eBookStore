<?php
    class DBC {
        private $hostname;
        private $username;
        private $password;
        private $dbname;
    
        protected function get_connect() {
            $this->hostname = "localhost";
            $this->username = "root";
            $this->password = "heoquay113";
            $this->dbname = "shop_db";
    
            $conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
            return $conn;
        }

        protected function check_data_null($temp_data) {
            return !isset($temp_data) || is_null($temp_data) || empty($temp_data);
        }
    }
?>