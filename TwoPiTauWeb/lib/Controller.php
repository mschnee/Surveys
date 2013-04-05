<?php
namespace com\twopitau\web\lib;

class Controller {
    protected $_request = array();
    protected $_isPost = false;
    protected $_layout = 'default';
    private $_data = array();
    private $_css = array();
    private $_js = array();
    private $_meta = array();
    public function defaultAction() {
        // does nothing;
    }
    
    /**
     * Wrapper that actuall calls an action function.
     * 
     * Actions are methods in the name of defaultAction().  We can call other
     * special functions, like defaultCacheKey() to determine is this content
     * has been requested recently, and defaultCacheTimeout() when adding to the cach.
     * @param type $actionName
     * @param type $params
     */
    public function invoke($actionName,$params=array()) {
        $cacheKeySuffix = "CacheKey";
        $this->_request = $params;
    }
    
    /**
     * If a cache key is supplied but no metyhod to save, do it automatically
     * @param type $cacheKey
     * @param type $data
     * @param type $timeout
     */
    private function _addToCache($cacheKey,$data,$timeout) {
        return Cache::instance()->write($cacheKey,$data,$timeout);
    }
    
    /**
     * If a cache key is provided but no method to read, do it automatically.
     * @param type $cacheKey
     */
    private function _readFromCache($cacheKey) {
        return Cache::instance()->read($cacheKey);
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
    
    protected function addMetaTag() {
        
    }
    
    protected function addJavascriptFile() {
        
    }
    
    protected function addJavascriptBlock() {
        
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