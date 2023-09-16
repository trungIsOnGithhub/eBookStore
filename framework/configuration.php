<?php

namespace LearnPHPMVC {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\Events as Events;
    use LearnPHPMVC\Configuration as Configuration;
    use LearnPHPMVC\Configuration\Exception as Exception;
    
    class Configuration extends Base {
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
            Events::fire("framework.configuration.initialize.before", array($this->type, $this->options));
            
            if(!$this->type) {
                throw new Exception\Argument("Invalid type");
            }
            
            Events::fire("framework.configuration.initialize.after", array($this->type, $this->options));
            
            switch ($this->type) {
                case "ini": {
                    return new Configuration\Driver\Ini($this->options);
                }
                default: {
                    throw new Exception\Argument("Invalid type");
                }
            }
        }
    }
}