<?php

namespace LearnPHPMVC\Cache {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\Cache\Exception as Exception;
    
    class Driver extends Base {
        public function initialize() {
            return $this;
        }
        
        // _getExceptionForImplementation inherited from parent class
    }
}