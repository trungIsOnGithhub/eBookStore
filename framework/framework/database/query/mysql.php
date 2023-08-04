<?php

namespace LearnPHPMVC\Database\SQLQuery {
    use LearnPHPMVC\Database as Database;
    use LearnPHPMVC\Database\Exception as Exception;
    
    class Mysql extends Database\SQLQuery {
        public function all() {
            $sql = $this->_buildSelect();
            $result = $this->connector->execute($sql);
            
            if($result === false) {
                $error = $this->connector->lastError;
                throw new Exception\Sql("There was an error with your SQL query: {$error}");
            }
            
            $rows = array();
            
            for ($i = 0; $i < $result->num_rows; $i++) {
                $rows[] = $result->fetch_array(MYSQLI_ASSOC);
            }
            
            return $rows;
        }
    }
}