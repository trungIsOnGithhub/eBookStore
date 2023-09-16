<?php

namespace LearnPHPMVC\Router\Route {
    use LearnPHPMVC\Router as Router;
    
    class Regex extends Router\Route {    
        /**
        * @readwrite
        */
        protected $_keys;
        
        public function matches($url) {
            $pattern = $this->pattern;
            
            $values = self::_matchRegexURL($pattern, $url);

            if( self::_hasRegexMatch($values) ) {
                // values found, modify parameters and return
                $derived = array_combine($this->keys, $values[1]);
                $this->parameters = array_merge($this->parameters, $derived);
                
                return true;
            }
            
            return false;
        }
    }
}