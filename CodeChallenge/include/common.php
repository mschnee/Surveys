<?php

/**
 * Automatically loads a class.
 * @param string $classname the name of the autoloader class.
 * @throw Exception if the class file cannot be found.
 **/
function __autoload($classname) {
    $dr = $_SERVER['DOCUMENT_ROOT'];
    $class = str_replace("_",DIRECTORY_SEPARATOR, $classname);
    if(file_exists("${dr}/include/${class}.php"))
        include "${dr}/include/${class}.php";
    else 
        throw new Exception("Could not load $classname");
}

date_default_timezone_set("America/Chicago");
/**
 * Debug a string, variable, or array to the configured log file.
 * Pulled from my personal repo for quick debugging.
 * @param mixed $variable String or array to output to logs
 * @param mixed $wrap True means wrap output in 120 = signs. String does the same with provided string
 **/
function debug( $variable, $wrap=null ) {
    $traces = debug_backtrace();
    $debugData = "";
    $dr = $_SERVER['DOCUMENT_ROOT'];
    if ( isset( $traces[0] ) ) {
        $debugData .= "on " . $file = str_replace( $dr, "", $traces[0]['file'] );
        if ( isset( $traces[1]['function'] ) )
            $debugData .= " inside " . $traces[1]['function'] . "()";
        $debugData .= " at line " . $line = $traces[0]['line'];
    }
    if (isset($GLOBALS["__log_file__"] ))
        $logFile = $dr."/logs/".$GLOBALS["__log_file__"];
    else {
        $logFile = $dr."/logs/php.log";
    }
    $date = date( "[d-M-Y H:i:s]" );
    if ( is_array( $variable ) ) {
        $logMessage = "$date DEBUG: [[ " . print_r( $variable, true ) . " ]] " . $debugData . "\n";
    } else {
        // quick shortcuts to make breaks when debugging.
        if ( $variable === '=' || $variable === '+' )
            $logMessage = str_repeat( $variable, 120 ) . " at line: $line of $file\n";
        else
            $logMessage = "$date DEBUG: [[ " . $variable . " ]] " . $debugData . "\n";
    }
    if ( $wrap === true )
        $wrap = "=";
    if ( $wrap )
        $logMessage = str_repeat( $wrap, 120 ) . "\n" . $logMessage . str_repeat( $wrap, 120 ) . "\n";
    error_log( $logMessage, 3, $logFile );
}