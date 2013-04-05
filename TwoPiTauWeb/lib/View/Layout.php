<?php
namespace com\twopitau\web\lib;

/**
 * A Layout is a special kind of view that renders another view.
 * 
 * In practice, when visiting some page, the controller will return what layout
 * to use, but the data from the controller will be passed to the view used
 * to render its action
 */
class View_Layout extends View {
    protected $_scriptDir = "layouts";
    protected $_controller = "";
    protected $_action = "";
    protected $_content = array();
    private $_d = array(
        'metaForLayout'=>'',
        'jsForLayout'=>'',
        'cssForLayout'=>'',
        'title'=>'',
        'jsBlock'=>''
    );
    
    public function __construct($controller,$action,$controllerData) {
        $this->_controller = $controller;
        $this->_action=$action;
        $this->_content=$controllerData;
    }
    
    /**
     * Called inside of the view script
     */
    public function content() {        
        $View = new View();
        $scriptFile = $View->getScript($this->_action,$this->_controller);
        return $View->render($scriptFile,$this->_content);
    }
    
    public function render($scriptFile) {
        extract($this->_d);
        
        /** RENDER **/
        ob_start();
        require($scriptFile);
        /** END RENDER **/
        
        foreach ( array_keys($this->_d) as $k) {
            if (isset($$k))
                unset($$k);
        }
        
        return ob_get_clean();
    }
    
    public function setData($d) {
        foreach($d as $k=>$v)
            $this->_d[$k] =$v;
    }
    
    public function set($k,$v) {
        $this->_d[$k] =$v;
    }
}