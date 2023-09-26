<?php

namespace LearnPHPMVC\Database\Connector {
    use LearnPHPMVC\Database as Database;
    use LearnPHPMVC\Database\Exception as Exception;
    
    class Mysql extends Database\Connector {
        protected $_charset = "utf8";
        /**
        * @readwrite
        */
        protected $_engine = "InnoDB";
        /**
        * @readwrite
        */
        protected $_isConnected = false;
        
        protected function _isValidService() {
            $isConfigured = $this->_isConfig();
            $isEmpty = empty($this->_connection);
            $isInstance = $this->_connection instanceof \MySQLi;
            
            if($this->isConnected && $isInstance && !$isEmpty) {
                return true;
            }
            
            return false;
        }
        
        public function connect() {
            if(!$this->_isValidService()) {
                $this->_connection = new \MySQLi(
                    $this->_host,
                    $this->_username,
                    $this->_password,
                    $this->_schema,
                    $this->_port
                );
                
                if( $this->_connection->connect_errno ) {
                    throw new Exception\Service("Unable to connect to database service.");
                }

                if( !$this->_connection->set_charset($this->_charset) ) {
                    throw new Exception\Service("Unable to set desired char_set for database service.");
                }
                
                $this->isConnected = true;
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
        
        public function query() {
            return new Database\SQLQuery\Mysql(array(
                "connector" => $this
            ));
        }
        
        public function execute($sql) {
            if(!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
            
            return $this->_connection->query($sql);
        }
        
        public function escape($value) {
            if(!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
            
            return $this->_connection->real_escape_string($value);
        }
        
        public function getLastInsertId() {
            if(!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
            
            return $this->_connection->insert_id;
        }
        
        public function getAffectedRows() {
            if(!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
            
            return $this->_connection->affected_rows;
        }
        
        public function getLastError() {
            if(!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
            
            return $this->_connection->error;
        }
        
        public function sync($model) {
            $lines = array();
            $indices = array();
            $columns = $model->columns;
            $template = "CREATE TABLE `%s` (\n%s,\n%s\n) ENGINE=%s DEFAULT CHARSET=%s;";
            
            foreach ($columns as $column) {
                $raw = $column["raw"];
                $name = $column["name"];
                $type = $column["type"];
                $length = $column["length"];
                
                if($column["primary"]) {
                    $indices[] = "PRIMARY KEY (`{$name}`)";
                }
                if($column["index"]) {
                    $indices[] = "KEY `{$name}` (`{$name}`)";
                }
                
                switch ($type) {
                    case "autonumber":
                    {
                        $lines[] = "`{$name}` int(11) NOT NULL AUTO_INCREMENT";
                        break;
                    }
                    case "text":
                    {
                        if($length !== null && $length <= 255)
                        {
                            $lines[] = "`{$name}` varchar({$length}) DEFAULT NULL";
                        }
                        else
                        {
                            $lines[] = "`{$name}` text";
                        }
                        break;
                    } 
                    case "integer":
                    {
                        $lines[] = "`{$name}` int(11) DEFAULT NULL";
                        break;
                    }
                    case "decimal":
                    {
                        $lines[] = "`{$name}` float DEFAULT NULL";
                        break;
                    }
                    case "boolean":
                    {
                        $lines[] = "`{$name}` tinyint(4) DEFAULT NULL";
                        break;
                    }
                    case "datetime":
                    {
                        $lines[] = "`{$name}` datetime DEFAULT NULL";
                        break;
                    }
                }
            }
            
            $table = $model->table;
            $sql = sprintf(
                $template,
                $table,
                join(",\n", $lines),
                join(",\n", $indices),
                $this->_engine,
                $this->_charset
            );
            
            $result = $this->execute("DROP TABLE ifEXISTS {$table};");
            if($result === false) {
                $error = $this->lastError;
                throw new Exception\SQL("There was an error in the query: {$error}");
            }
            
            $result = $this->execute($sql);
            if($result === false) {
                $error = $this->lastError;
                throw new Exception\SQL("There was an error in the query: {$error}");
            }
            
            return $this;
        }
    }
}