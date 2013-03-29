<?php
namespace com\twopitau\web;
//$Log = new Log('php.log');

/**
 * Autoloader for TPT library classes.
 * @param type $className
 */
function tpt_autoload($namespacedClassName) {
    $fnames = explode("\\",$namespacedClassName);
    $names = $fnames;
    $className = array_pop($names);
    $dirName = array_pop($names);
    $specialClasses = array(
        "Controller"=>"controllers",
        "Model"=>"models"
    );
    $sep =DIRECTORY_SEPARATOR;
    $rpath = PATH . "{$dirName}{$sep}";
    foreach($specialClasses as $name=>$dir) {
        if(strlen($name)<strlen($className) and substr($className,-strlen($name))==$name) {
            $rpath = PATH . "{$dir}{$sep}";
        }
    }
    $constructedPath = str_replace("_",DIRECTORY_SEPARATOR, $className);
    $extension = ".php";
    if(file_exists("{$rpath}{$constructedPath}{$extension}"))
        include "{$rpath}{$constructedPath}{$extension}";
}

spl_autoload_register('\com\twopitau\web\tpt_autoload');

include_once PATH . 'etc' . DIRECTORY_SEPARATOR .'util.php';