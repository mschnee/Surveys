<?php
/*
    Question 3: Using the array given in Question 2, sort the array in 
    descending order, exclude duplicate values. 
    
    ----------------------------------------------------------------------------
    Unlike question 2, where I demonstrated two stable quicksort algorithms,
    one O(n)-space and one O(1)-space, this question is asking for an unstable
    sort that discards duplicate values.  Keeping quicksort O(1)-space and 
    unstable greatly adds to the complexity.
    
    Additionally, PHP provides functions for this.  array_unique() will remove 
    all duplicate entries in an array, and rsort() will reverse-sort the array.
*/
$linebreak = PHP_SAPI=='cli'?"\n":"<br/>";

function PrintArray($arr) {
    return "{".implode(",",$arr)."}";
}
$testarr = array(4,10,8,34,35,12,1,9,8,14,28);

// O(n) space quicksort
function quickrsort($arr) {
    global $linebreak;
    if(count($arr) <=1)
        return $arr;
    
    $pivot = $arr[ (count($arr)/2)-1 ];
    
    $less = array();
    $greater = array();
    
    $middle = array($pivot);
    foreach($arr as $k=>$i) {
        if($i==$pivot) {
            // do nothing with duplicates - unstable sort.
        } elseif($i>$pivot) {
            $less[]= $i;
        } else {
            $greater[]=$i;
        }
    }
    return array_merge(quickrsort($less),$middle,quickrsort($greater) );
}

$answer1 = array_unique($testarr);
$answer2 = $testarr;


rsort($answer1);
$answer2 = quickrsort($answer2);

echo "Question 2 $linebreak";
echo "         rsort(".PrintArray($testarr).") = ".PrintArray($answer1).$linebreak;
echo "    quickrsort(".PrintArray($testarr).") = ".PrintArray($answer2).$linebreak;