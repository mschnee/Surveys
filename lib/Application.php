<?php
/**
 * @file config.php
 */
namespace com\twopitau\web\lib;
class Application {
    protected $_Log;
    
    private function __construct() {
        
    }
    public static function instance() {
        static $App = null;
        if($App===null)
            $App = new Application();
        return $App;
    }
    
    /**
     * Executes the application.
     * @staticvar boolean $runOnce  Sentinal.
     * @return type
     */
    static function exec() {
        static $runOnce = true;
        if($runOnce) {
            $App = self::instance();
            $runOnce=false;
            return $App->run();
        }
        
    }
    
     public function run() {
        $this->_Log = new Log( '/tmp' . DIRECTORY_SEPARATOR . 'tpt.log');
        $this->_Log->install();
        
        list($renderType,$controllerName,$actionName) = $this->getFullRouteArrayFromServer();
                
        $Controller =$this->getControllerObject($controllerName);
        
        if( isset($_POST) and !empty($_POST) ) {
            $Controller->isPost(true);
        }
        $Controller->invoke($actionName,$_REQUEST);
        $layoutName = $Controller->getLayout();
        $Layout = new View_Layout($controllerName,$actionName,$Controller->getData());
        $scriptFile = $Layout->getScript($layoutName);
        
        $Layout->setData(array());
        echo $Layout->render($scriptFile);
     }
     
     /**
      * Takes a string in URL_PATH format and turns it into a route array.
      * 
      * Route arrays begin with either /HTML or /JSON, and are in the format
      * /[HTML|JSON]/Controller/Action
      * @ret array
      */
     public function getFullRouteArrayFromString($str) {
         if(!$str or empty($str)) {
             return array('Html','Default','default');
         }
         $tokens = explode("/",$str);
         
         if(!$tokens or empty($tokens)) {
             return array('Html','Default','default');
         }
         
         $renderType = 'Html';
         if(in_array(strtolower($tokens[0]),array('html','json'))) {
             $renderType = (strtolower($tokens[0])=='json')?'Json':'Html';
             array_shift($tokens);
         }
         $controller = 'Default';
         $action = 'default';
         if(count($tokens)) {
             $controller = $tokens[0];
             array_shift($tokens);
         }
         if(count($tokens)) {
             $action = $tokens[0];
             array_shift($tokens);
         }
         
         return array($renderType,$controller,$action);
     }
     
     /**
      * Returns a route array from parsing $_SERVER and $_REQUEST
      * @return full-route array('Html','Default','default')
      */
     public function getFullRouteArrayFromServer() {
         return $this->getFullRouteArrayFromString($this->getRouteStringFromServer());
     }
     
     /**
      * Takes a string in the form of controller/action
      * @param type $str
      * @return type
      */
     public function getRouteArrayFromString($str) {
         $r = $this->getFullRouteArrayFromString($str);
         array_shift($r);
         return $r;
     }
     /**
      * Returns a route array from parsing $_SERVER and $_REQUEST sans renderType.
      * @return full-route array('Default','default')
      */
     public function getRouteArrayFromServer() {
         $r = $this->getFullRouteArrayFomServer();
         array_shift($r);
         return $r;
     }
     
     /**
      * Determines the appropriate route string from $_SERVER and $_REQUEST
      * @return string
      */
     public function getRouteStringFromServer() {
         $script = $_SERVER['SCRIPT_NAME'];
         $uri = $_SERVER['REQUEST_URI'];
         $uri = str_replace($script,'',$uri);
         list($route,$args) = explode('?',$uri.'?');
         if(empty($route) and isset($_REQUEST['route'])) {
             $route = $_REQUEST['route'];
             unset($_REQUEST['route']);
         } else {
             $route = '';
         }
         return $route;
     }
     
     public function getControllerObject($controllerName) {
         $name = $this->getProperControllerName($controllerName);
         
         $Controller = null;
         try {
            $Controller = new $name;
         } catch (Exception $e) {
             Log::debug($e->getMessage());
             $Controller = new com\twopitau\web\controllers\DefaultController;
         }
         return $Controller;
     }
     
     /**
      * If you want some kind of special overrides, put them here.
      * @advanced 
      * @param type $controllerName
      */
     public function getProperControllerName($controllerName) {
         return "\\com\\twopitau\\web\\controllers\\".$controllerName."Controller";
     }
     
     /**
      * Renders a simple fragment
      * @param type $route
      * @param type $params
      * @return type
      */
     public function requestFragment($route,$params) {
         list($controllerName,$actionName) = $this->getRouteArrayFromString($route);
                
        $Controller =$this->getControllerObject($controllerName);
        $Controller->invoke($actionName,$params);
        
        $View = new View();
        $scriptFile = $View->getScript($actionName,$controllerName);
        return $View->render($scriptFile,$Controller->getData());
     }
}