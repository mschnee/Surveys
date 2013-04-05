<?php
/*
    Question 1:
    Write a function that will take a string of at least 5 characters and 
    reverse it. 
    (For example: “I am here, looking at you” :: “uoy ta gnikool ,ereh ma I”)
    
    ----------------------------------------------------------------------------
    Reversing an array, or string, is an O(N) operation where elements are
    switched.  I have implemented a reverse function that is O(1)-space.
    
    Additionally, PHP provides the strrev() function, which returns a reversed
    string.
*/
$linebreak = PHP_SAPI=='cli'?"\n":"<br/>";
function reverse(&$str) {
    if(strlen($str)<=1)
        return $str;
    // O(n) space aware
    for($i=0; $i<(strlen($str)/2);$i++) {
        $temp = $str[$i];
        $str[$i] = $str[strlen($str)-($i+1)];
        $str[strlen($str)-($i+1)] = $temp;
    }
}

$testString = "I am here, looking at you";
$answer1 = $testString;
reverse($answer1);
echo "Question 1 $linebreak";
echo "reverse(\"{$testString}\") = \"" .$answer1."\"".$linebreak;
echo " strrev(\"{$testString}\") = \"" .strrev($testString)."\"".$linebreak;