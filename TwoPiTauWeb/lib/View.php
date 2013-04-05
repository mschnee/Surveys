<?php
namespace com\twopitau\web\lib;

/**
 * @class View
 * The View class is responsible for the drawing of things to the screen!
 */
class View {
    protected $_scriptDir = "scripts";
    
    protected $subDir = "";
    
    public function __construct() {
        
    }
    
    public function render($scriptFile,$data) {
        extract($data);
        
        /** RENDER **/
        ob_start();
        require($scriptFile);
        /** END RENDER **/
        
        foreach ($data as $k => $v) {
            if (isset($$k))
                unset($$k);
        }
        return ob_get_clean();
    }
    
    public function requestElement($route,$params) {
        $app = Application::instance();
        $data = $app->getData($route,$params);
        list($controller,$action) = $app->getRouteArrayFromString($route);
        
        $View = new View();
        $scriptFile = $this->getScript($action,$controller);
        return $this->render($scriptFile,$data);
    }
    
    /**
     * Tries all possible script locations, from the most specific to the least specific. 
     * @param type $base optional
     * @param type $name can include a directory separator if you want, but it might get funky.
     * @return null
     */
    public function getScript($name,$base=null){
        $scriptRoot = PATH . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR;
        $ext = ".phtml";
        if($base and file_exists($scriptRoot.DIRECTORY_SEPARATOR.$this->_scriptDir.DIRECTORY_SEPARATOR.$base.DIRECTORY_SEPARATOR.$name.$ext)){   
            return $scriptRoot.DIRECTORY_SEPARATOR.$this->_scriptDir.DIRECTORY_SEPARATOR.$base.DIRECTORY_SEPARATOR.$name.$ext;
        }elseif($base and file_exists($scriptRoot.DIRECTORY_SEPARATOR.$this->_scriptDir.DIRECTORY_SEPARATOR.$base.DIRECTORY_SEPARATOR."default".$ext)) {
            return $scriptRoot.DIRECTORY_SEPARATOR.$this->_scriptDir.DIRECTORY_SEPARATOR.$base.DIRECTORY_SEPARATOR."default".$ext;
        }elseif(file_exists($scriptRoot.DIRECTORY_SEPARATOR.$this->_scriptDir.DIRECTORY_SEPARATOR.$name.$ext)) {
            return $scriptRoot.DIRECTORY_SEPARATOR.$this->_scriptDir.DIRECTORY_SEPARATOR.$name.$ext;
        } elseif(file_exists($scriptRoot.DIRECTORY_SEPARATOR.$this->_scriptDir.DIRECTORY_SEPARATOR.'default'.$ext)) {
            $scriptRoot.DIRECTORY_SEPARATOR.$this->_scriptDir.DIRECTORY_SEPARATOR.'default'.$ext;
        } elseif(file_exists($scriptRoot.DIRECTORY_SEPARATOR.'default'.$ext)) {
            $scriptRoot.DIRECTORY_SEPARATOR.$name.$ext;
        } elseif(file_exists($scriptRoot.DIRECTORY_SEPARATOR.'default'.$ext)) {
            $scriptRoot.DIRECTORY_SEPARATOR.'default'.$ext;
        } else return null;
    }
}