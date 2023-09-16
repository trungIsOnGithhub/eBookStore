<?php

namespace LearnPHPMVC\Router {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\Router\Exception as Exception;
    
    class Route extends Base {
        /**
        * @readwrite
        */
        protected $_pattern;
        /**
        * @readwrite
        */
        protected $_controller;
        /**
        * @readwrite
        */
        protected $_action;
        /**
        * @readwrite
        */
        protected $_parameters = array();

        protected static function _hasRegexMatch($arrayValues) {
            return count($arrayValues) && count($arrayValues[0]) && count($arrayValues[1]);
        }

        protected static function _matchRegexURL($pattern, $url) {
            $value = [];
            preg_match_all("#^{$pattern}$#", $url, $values);

            return $values;
        }

        public function _getExceptionForImplementation($method) {
            return new Exception\Implementation("{$method} method have not been implemented");
        }
    }
}