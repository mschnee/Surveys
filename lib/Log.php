<?php

namespace com\twopitau\web\lib;

class Log {

    protected $logFile;
    protected static $logpath = null;
    
    /**
     *A list of all the error types that might arise.
     * @var type 
     */
    protected static $errorType = array(
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSING ERROR',
        E_NOTICE => 'NOTICE',
        E_CORE_ERROR => 'CORE ERROR',
        E_CORE_WARNING => 'CORE WARNING',
        E_COMPILE_ERROR => 'COMPILE ERROR',
        E_COMPILE_WARNING => 'COMPILE WARNING',
        E_USER_ERROR => 'USER ERROR',
        E_USER_WARNING => 'USER WARNING',
        E_USER_NOTICE => 'USER NOTICE',
        E_STRICT => 'STRICT NOTICE',
        E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
        E_DEPRECATED => "DEPRECATED",
        E_USER_DEPRECATED => "USER DEPRECATED",
        E_ALL => "ALL"
    );

    /**
     * The default list of allowed error types.  These will automatically
     * end up in the log file.
     * @var type 
     */
    protected static $allowed = array(E_ERROR, E_WARNING, E_PARSE, E_NOTICE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING);
    
    /**
     * The list of shutdown errors to report.
     * @var type 
     */
    protected static $allowedOnShutdown = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR);
    
    /**
     * An array of filters.  Automatically generated messages that meet a filter
     * configured here won't end up in the log.
     * 
     * Notable because PHP's postgresql library is notoriously bad at error-handling.
     * @var type 
     */
    protected static $filters = array(
		'/pg_exec\(\)/',
		'/already defined$/'
    );
    
    /**
     * Constructs the log object
     */
    public function __construct($file) {
        self::$logpath = $file;
    }

    public function install() {
        set_error_handler(array($this, "error"));
        set_exception_handler(array($this, "exception"));
        register_shutdown_function(array($this, "shutdown"));
    }

    public function shutdown() {
        $error = error_get_last();
        if (in_array($error['type'], self::$allowedOnShutdown)) {
            self::write(
                    self::flog(": [[ " . $error['message'] . " ]]", self::$errorType[$error['type']], $error['file'], $error['line'])
            );
        }
    }

    public function error($errorLevel, $message, $fileName, $lineNumber, $context) {
        if (!in_array($errorLevel, self::$allowed)) {
            return true;
        }
        foreach (self::$filters as $filter) {
            if (preg_match($filter, $message)) {
                return true;
            }
        }
        $etype = self::$errorType[$errorLevel];
        //$trace = debug_backtrace();
        $trace = $context;
        while (false /* !empty($trace) and (( isset($trace[0]['class']) and $trace[0]['class']=="FR_Core_Log") or  (isset($trace[0]['file']) and substr($trace[0]['file'],-7) == "Log.php") ) */) {
            self::log($trace);
            array_shift($trace);
        }
        self::write( self::flog($message, $etype, $fileName, $lineNumber) );
        return true;
    }

    public function exception(Exception $e) {
        self::log($e->getMessage(), get_class($e), $e->getTrace());
    }

    public static function debug($variable){
        $bt = debug_backtrace();
        array_shift($bt);  // remove this.
        self::log($variable,"DEBUG",$bt);
    }
    public static function trace($variable) {
        $bt = debug_backtrace();
        array_shift($bt);  // remove this.
        self::log($variable,"TRACE",$bt);
    }
    
    public static function vtrace($variable) {
        $bt = debug_backtrace();
        array_shift($bt);  // remove this.
        self::log(self::clean_backtrace(2),"VERBOSE TRACE BACKTRACE",$bt);
        self::log($variable,"VERBOSE TRACE",$bt);
    }
    
    public static function clean_backtrace($count = 1) {
            $D = debug_backtrace();
            $ret =array();
            /** remove the call to clean_backtrace */
            for($i =0; $i<$count;$i++)
                array_shift($D);
            
            /** flip the order, older at the top */
            foreach ($D as $d) {
                array_unshift($ret,"{$d['file']}::{$d['line']} ".( (isset($d['class']) and $d['class'])?"{$d['class']}{$d['type']}":" ")."{$d['function']}(" .json_encode($d['args']).")");
            }
            return $ret;
    }
    /**
     * 	Logs a message to a R/W file in ./tmp?
     */
    public static function log($variable, $type = "debug", $traces = array()) {
        // unimplemented, this might not be a good idea.

        $debugData = '';
        $file = null;
        $line = null;

        if (empty($traces))
            $traces = debug_backtrace();

        if (isset($traces[0])) {
            $file = str_replace(PATH, "", $traces[0]['file']);
            if (isset($traces[1]['function']))
                $debugData .= " inside " . $traces[1]['function'] . "()";
            $line = $traces[0]['line'];
        }

        if (is_array($variable)) {
            $logMessage = ": [[ " . print_r($variable, true) . " ]] " . $debugData . "\n";
        } elseif (is_object($variable)) {
            ob_start();
            var_dump($variable);
            $logMessage = ": [[ " . ob_get_clean() . " ]] " . $debugData . "\n";
        } else {
            // quick shortcuts to make breaks when debugging.
            if ($variable === '=' || $variable === '+')
                $logMessage = str_repeat($variable, 120) . "\n";
            else
                $logMessage = ": [[ " . $variable . " ]] " . $debugData . "\n";
        }
        /* breaks ajax
          echo $logMessage . "<BR>";
          if ((APP_SERVER_ENV == 'local') && (strtoupper($type) !='NOTICE') && (strtoupper($type) !='DEBUG')) {
          echo "<pre>{$logMessage}</pre>" ;
          $traced = print_r($traces,true);
          echo "<pre>{$traced}</pre>";
          }
          // */
        self::write( self::flog($logMessage, $type, $file, $line) );
    }

    /**
     * Format log entry.
     * @param type $message
     * @param type $type
     * @param type $file
     * @param type $line
     * @return type
     */
    protected static function flog($message, $type, $file, $line) {
        $date = date("[d-M-Y H:i:s]");
        return $date . " " . ($type ? strtoupper($type) : "") . " " . $message . " " . ($line ? "at line: $line of " : "") . ($file ? ($line ? " of $file" : " in $file") : "") . "\n";
    }

    public static function write($message) {
        if (self::$logpath === null) {
            self::$logpath = "/tmp/tpt.log";
        }
        if (PHP_SAPI == 'cli') {
            echo $message;
        }else{
            error_log($message, 3, self::$logpath);
        }
    }

}