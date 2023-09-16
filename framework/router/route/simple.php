<?php

namespace LearnPHPMVC\Router\Route {
    use LearnPHPMVC\Router as Router;
    use LearnPHPMVC\ArrayMethods as ArrayMethods;
    
    class Simple extends Router\Route {
        public function matches($url) {
            $pattern = $this->pattern;
            
            // get keys
            preg_match_all("#:([a-zA-Z0-9]+)#", $pattern, $keys);
            
            if(sizeof($keys) && sizeof($keys[0]) && sizeof($keys[1])) {
                $keys = $keys[1];
            }
            else {
                // no keys in the pattern, return a simple match
                return preg_match("#^{$pattern}$#", $url);
            }
            
            // normalize route pattern
            $pattern = preg_replace("#(:[a-zA-Z0-9]+)#", "([a-zA-Z0-9-_]+)", $pattern);

            $values = self::_matchRegexURL($pattern, $url);

            if( self::_hasRegexMatch($values) ) { 
                // values found, modify parameters and return
                $derived = array_combine($keys, ArrayMethods::flatten($values));
                $this->parameters = array_merge($this->parameters, $derived);
                
                return true;
            }

            return false;
        }
    }
}