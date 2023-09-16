<?php

namespace LearnPHPMVC {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\Events as Events;
    use LearnPHPMVC\Registry as Registry;
    use LearnPHPMVC\Database as Database;
    use LearnPHPMVC\Database\Exception as Exception;
    
    class Database extends Base {
        /**
        * @readwrite
        */
        protected $_type;
        
        /**
        * @readwrite
        */
        protected $_options;
        
        // _getExceptionForImplementation inherited from parent class

        public function initialize() {
            Events::fire("framework.database.initialize.before", array($this->type, $this->options));
        
            if(!$this->type) {
                $configuration = Registry::get("configuration");
                
                if($configuration) {
                    $configuration = $configuration->initialize();
                    $parsed = $configuration->parse("configuration/database");
                    
                    if(!empty($parsed->database->default) && !empty($parsed->database->default->type))
                    {
                        $this->type = $parsed->database->default->type;
                        unset($parsed->database->default->type);
                        $this->options = (array) $parsed->database->default;
                    }
                }
            }
            
            if(!$this->type) {
                throw new Exception\Argument("Invalid type");
            }
            
            Events::fire("framework.database.initialize.after", array($this->type, $this->options));
            
            switch ($this->type) {
                case "mysql": {
                    return new Database\Connector\Mysql($this->options);
                }
                default: {
                    throw new Exception\Argument("Invalid type");
                }
            }
        }
    }
}