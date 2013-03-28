<?php
/**
 * @file index.php
 */
use \com\twopitau\web\lib as Lib;
/** index.php should be at $root/www/index.php */
define('PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR. '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
require_once( PATH . "etc" . DIRECTORY_SEPARATOR . "config.php");

return Lib\Application::exec();