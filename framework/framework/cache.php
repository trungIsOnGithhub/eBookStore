<?php

namespace LearnPHPMVC {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\Cache as Cache;
    use LearnPHPMVC\Events as Events;
    use LearnPHPMVC\Registry as Registry;
    use LearnPHPMVC\Cache\Exception as Exception;
    
    class Cache extends Base {
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
            Events::fire("framework.cache.initialize.before", array($this->type, $this->options));
        
            if(!$this->type) {
                $configuration = Registry::get("configuration");
                
                if($configuration) {
                    $configuration = $configuration->initialize();
                    $parsed = $configuration->parse("configuration/cache");
                    
                    if(!empty($parsed->cache->default) && !empty($parsed->cache->default->type)) {
                        $this->type = $parsed->cache->default->type;
                        unset($parsed->cache->default->type);
                        $this->options = (array) $parsed->cache->default;
                    }
                }
            }
            
            if(!$this->type) {
                throw new Exception\Argument("Invalid type");
            }
            
            Events::fire("framework.cache.initialize.after", array($this->type, $this->options));
            
            switch ($this->type) {
                case "memcached": {
                    return new Cache\Driver\Memcached($this->options);
                }
                default: {
                    throw new Exception\Argument("Invalid type");
                }
            }
        }
    }
}