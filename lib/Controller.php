<?php
namespace com\twopitau\web\lib;

class Controller {
    protected $_request = array();
    protected $_isPost = false;
    protected $_layout = 'default';
    private $_data = array();
    
    public function defaultAction() {
        // does nothing;
    }
    
    public function invoke($actionName,$params=array()) {
        $this->_request = $params;
    }
    
    public function isPost($p) {
        $this->_isPost = (bool)$p;
    }
    
    protected function getRequest() {
        return $this->_request;
    }
    
    protected function addData($key,$v) {
        $this->_date[$key] = $v;
    }
    
    public function getData($key=null) {
        if($key===null)
            return $this->_data;
        elseif(isset($this->_data[$key]))
            return $this->_data[$key];
        else return null;
    }
    
    /**
     * An action may set a different master layout.
     * @return type
     */
    public function getLayout() {
        return $this->_layout;
    }
}