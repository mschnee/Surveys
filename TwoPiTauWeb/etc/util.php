<?php
namespace com\twopitau\web\util;

/**
 * Quick function to check an array's content by key
 * @param type $arr
 * @param type $key
 * @param type $fallback
 * @return type
 */
function ifEmpty($arr,$key,$fallback=null) {
    if(!isset($arr))
        return $fallback;
    if(!is_scalar($arr)) {
        return $arr;
    } elseif(is_array($arr)) {
        if(isset($arr[$key]))
            return $arr[$key];
        else return $fallback;
    }elseif(is_object($arr)) {
        if(isset($arr->$key))
            return $arr->$key;
        else return $fallback;
    } else return $fallback;
}