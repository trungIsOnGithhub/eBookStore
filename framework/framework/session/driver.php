<?php

namespace LearnPHPMVC\Session {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\Session\Exception as Exception;
    
    class Driver extends Base {
        public function initialize() {
            return $this;
        }
        
        // _getExceptionForImplementation inherited from parent class
    }
}