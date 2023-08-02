<?php

namespace LearnPHPMVC\Configuration {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\Configuration\Exception as Exception;
    
    class Driver extends Base {
        protected $_parsed = array();
        
        public function initialize() {
            return $this;
        }
        
        // _getExceptionForImplementation inherited from parent class
    }
}