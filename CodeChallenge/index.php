<?php
/*******************************************************************************
 * Code Challenge
 * Matthew Schnee
 ******************************************************************************/
 
/* set include pathing */
{ 
    $paths = array(
      "include",
      get_include_path()
  );
  set_include_path(implode(PATH_SEPARATOR, $paths));
}

include_once 'common.php';
$table = new Table($_REQUEST);
$table();