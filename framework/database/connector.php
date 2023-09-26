<?php

namespace LearnPHPMVC\Database {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\Database\Exception as Exception;
    
    abstract class Connector extends Base {
        protected $_connection;
        /**
        * @readwrite
        */
        protected $_host;
        /**
        * @readwrite
        */
        protected $_username;
        /**
        * @readwrite
        */
        protected $_password;
        /**
        * @readwrite
        */
        protected $_schema;
        /**
        * @readwrite
        */
        protected $_port;
        /**
        * @readwrite
        */
        protected $_isConnected = false;


        protected abstract function _isValidService();
        
        public abstract function connect();

        public function _isConfig() {
            $this->_host = $host;
            $this->_username = $username;
            $this->_password = $password;
            $this->_schema = $schema;
            $this->_port = $port;
            return isset($this->_host) && isset($this->_username) && isset($this->password) && isset($this->_schema) && isset($this->_port);
        }

        public function _config($host, $username, $password, $schema, $port) {
            $this->host = $host;
            $this->username = $username;
            $this->password = $password;
            $this->schema = $schema;
            $this->port = $port;
            return $this;
        }
        // _getExceptionForImplementation inherited from parent class
    }
}