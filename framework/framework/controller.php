<?php

namespace LearnPHPMVC {
    use LearnPHPMVC\Base as Base;
    use LearnPHPMVC\View as View;
    use LearnPHPMVC\Events as Events;
    use LearnPHPMVC\Registry as Registry;
    use LearnPHPMVC\Template as Template;
    use LearnPHPMVC\Controller\Exception as Exception;
    
    class Controller extends Base {
        /**
        * @read
        */
        protected $_name;
        
        /**
        * @readwrite
        */
        protected $_parameters;
        
        /**
        * @readwrite
        */
        protected $_layoutView;
        
        /**
        * @readwrite
        */
        protected $_actionView;
        
        /**
        * @readwrite
        */
        protected $_willRenderLayoutView = true;
        
        /**
        * @readwrite
        */
        protected $_willRenderActionView = true;
        
        /**
        * @readwrite
        */
        protected $_defaultPath = "application/views";
        
        /**
        * @readwrite
        */
        protected $_defaultLayout = "layouts/standard";
        
        /**
        * @readwrite
        */
        protected $_defaultExtension = "html";
        
        /**
        * @readwrite
        */
        protected $_defaultContentType = "text/html";
        
        protected function getName() {
            if(empty($this->_name)) {
                $this->_name = get_class($this);
            }
            return $this->_name;
        }
        
        // _getExceptionForImplementation inherited from parent class
        
        public function __construct($options = array()) {
            parent::__construct($options);
            
            Events::fire("framework.controller.construct.before", array($this->name));
            
            if($this->willRenderLayoutView) {        
                $defaultPath = $this->defaultPath;
                $defaultLayout = $this->defaultLayout;
                $defaultExtension = $this->defaultExtension;
                
                $view = new View(array(
                    "file" => APP_PATH."/{$defaultPath}/{$defaultLayout}.{$defaultExtension}"
                ));
                
                $this->layoutView = $view;
            }
            
            if($this->willRenderActionView) {  
                $router = Registry::get("router");
                $controller = $router->controller;
                $action = $router->action;
                
                $view = new View(array(
                    "file" => APP_PATH."/{$defaultPath}/{$controller}/{$action}.{$defaultExtension}"
                ));
                
                $this->actionView = $view;
            }
            
            Events::fire("framework.controller.construct.after", array($this->name));
        }
        
        public function render() {
            Events::fire("framework.controller.render.before", array($this->name));
            
            $defaultContentType = $this->defaultContentType;
            $results = null;
            
            $doAction = $this->willRenderActionView && $this->actionView;
            $doLayout = $this->willRenderLayoutView && $this->layoutView;
            
            try {
                if($doAction) {
                    $view = $this->actionView;
                    $results = $view->render();
                    
                    $this
                        ->actionView
                        ->template
                        ->implementation
                        ->set("action", $results);
                }
                
                if($doLayout) {
                    $view = $this->layoutView;
                    $results = $view->render();
                    
                    header("Content-type: {$defaultContentType}");
                    echo $results;
                }
                else if($doAction) {
                    header("Content-type: {$defaultContentType}");
                    echo $results;
                }
                
                $this->willRenderLayoutView = false;
                $this->willRenderActionView = false;
            }
            catch (\Exception $e) {
                throw new View\Exception\Renderer("Invalid layout/template syntax");
            }
            
            Events::fire("framework.controller.render.after", array($this->name));
        }
        
        public function __destruct() {
            Events::fire("framework.controller.destruct.before", array($this->name));
            
            $this->render();
            
            Events::fire("framework.controller.destruct.after", array($this->name));
        }
    }
}