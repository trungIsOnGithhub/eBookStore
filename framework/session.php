<?php

namespace LearnPHPMVC {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\Events as Events;
    use LearnPHPMVC\Session as Session;
    use LearnPHPMVC\Registry as Registry;
    use LearnPHPMVC\Session\Exception as Exception;

    class Session extends Base {
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
            Events::fire("framework.session.initialize.before", array($this->type, $this->options));
            
            if(!$this->type) {
                $configuration = Registry::get("configuration");
                
                if($configuration) {
                    $configuration = $configuration->initialize();
                    $parsed = $configuration->parse("configuration/session");
                    
                    if(!empty($parsed->session->default) && !empty($parsed->session->default->type))
                    {
                        $this->type = $parsed->session->default->type;
                        unset($parsed->session->default->type);
                        $this->options = (array) $parsed->session->default;
                    }
                }
            }
            
            if(!$this->type) {
                throw new Exception\Argument("Invalid type");
            }
            
            Events::fire("framework.session.initialize.after", array($this->type, $this->options));
            
            switch ($this->type) {
                case "server": {
                    return new Session\Driver\Server($this->options);
                }
                default: {
                    throw new Exception\Argument("Invalid type");
                }
            }
        }
    }
}