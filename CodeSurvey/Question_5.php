<?php
/*
    Question 5: Provide three different ways to print whole numbers divisible 
    by 2, starting with 1 and going through 20.  
    
    ----------------------------------------------------------------------------
    2 is a magic number with all kinds of special properties that are unique to
    it.  Want the sum of all the numbers 1 to 100?  It's (100^2)/2.
    
    The iterative solution is to just inspect each element, one at a time, and
    validate it against the condition (that it is divisible by 2).
    
    The easiest solutions are to use array_map and array_filter.  Array_map()
    applies a function to every element in the array in parallel- Since 2 is a 
    special number, as we're operating in a known range, all we have to do is
    use half the range and double every element in it.
    
    Finally, there's array_filter(), which removes elements from a range given
    a function that validates items.  This is essentially the same as the 
    foreach() version, though there's likely some performance benefits if
    it's properly parallelized.
*/
$linebreak = PHP_SAPI=='cli'?"\n":"<br/>";

function PrintArray($arr) {
    return "{".implode(",",$arr)."}";
}

$range = range(1,20);
$result1 = array();
$result2 = array();
$result3 = array();

// Result 1
foreach($range as $i) {
        if($i%2==0)
            $result1[] = $i;
}

// Result 2
$valer = max($range)/2;
$dbl = function($v) {
    return($v*2);
};
$result2 = array_map($dbl,range(1,$valer));

// Result 3
function twocheck($v) {
    return($v%2==0);
};
$result3 = array_filter($range,"twocheck");

echo "The original range :".PrintArray($range).$linebreak;
echo "           foreach :".PrintArray($result1).$linebreak;
echo "         array_map :".PrintArray($result2).$linebreak;
echo "     array_filtere :".PrintArray($result3).$linebreak;
echo $linebreak;