<?php

namespace LearnPHPMVC\Cache\Driver {
    use LearnPHPMVC\Cache as Cache;
    use LearnPHPMVC\Cache\Exception as Exception;
    
    class Memcached extends Cache\Driver {
        protected $_connection;
        /**
        * @readwrite
        */
        protected $_host = "127.0.0.1";
        /**
        * @readwrite
        */
        protected $_port = "11211";
        /**
        * @readwrite
        */
        protected $_isConnected = false;
        
        protected function _isValidService() {
            $isEmpty = empty($this->_connection);
            $isInstance = $this->_connection instanceof \Memcache;
            
            if($this->isConnected && $isInstance && !$isEmpty) {
                return true;
            }
            
            return false;
        }
        
        public function connect() {
            try {
                $this->_connection = new \Memcache();
                $this->_connection->connect(
                    $this->host,
                    $this->port
                );
                $this->isConnected = true;
            }
            catch (\Exception $e) {
                throw new Exception\Service("Unable to connect to service");
            }
            
            return $this;
        }
        
        public function disconnect() {
            if($this->_isValidService()) {
                $this->_connection->close();
                $this->isConnected = false;
            }
            
            return $this;
        }
        
        public function get($key, $default = null) {
            if(!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
        
            $value = $this->_connection->get($key, MEMCACHE_COMPRESSED);
            
            if($value) {
                return $value;
            }
            
            return $default;
        }
        
        public function set($key, $value, $duration = 120) {
            if(!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
        
            $this->_connection->set($key, $value, MEMCACHE_COMPRESSED, $duration);
            return $this;
        }
        
        public function erase($key) {
            if(!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
            
            $this->_connection->delete($key);
            return $this;
        }
    }
}
