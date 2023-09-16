<?php

namespace LearnPHPMVC {
    class Registry {
        private static $_instances = array();
        
        private function __construct() {}
        
        private function __clone() {}
        
        public static function get($key, $default = null) {
            return ( isset(self::$_instances[$key]) ) ? self::$_instances[$key] : $default;
        }
        
        public static function set($key, $instance = null) {
            self::$_instances[$key] = $instance;
        }
        
        public static function erase($key) {
            unset(self::$_instances[$key]);
        }
    }
}