<?php
/*
    Question 2: Take the following single dimension array and sort in 
    ascending order: 
    { 4, 10, 8, 34, 35, 12, 1, 9, 8, 14, 28 } 
    
    ----------------------------------------------------------------------------
    There are a number fo classic sorts available for this: mergesort, radix, 
    quicksort.  I have implemented two variants of the quicksort algorithm,
    one of them (quicksort) is O(n)-space,and the other (o1_quicksort) is 
    O(1)-space.
    
    Additionally, PHP provides the sort() function, which is likely preferable
    for performance reasons.
*/
$linebreak = PHP_SAPI=='cli'?"\n":"<br/>";

function PrintArray($arr) {
    return "{".implode(",",$arr)."}";
}
$testarr = array(4,10,8,34,35,12,1,9,8,14,28);

// O(n) space quicksort
function quicksort($arr) {
    global $linebreak;
    if(count($arr) <=1)
        return $arr;
    
    $pivot = $arr[ (count($arr)/2)-1 ];
    
    $less = array();
    $greater = array();
    
    $middle = array();
    foreach($arr as $k=>$i) {
        if($i==$pivot)
            $middle[]=$i;
        elseif($i<$pivot) {
            $less[]= $i;
        } else {
            $greater[]=$i;
        }
    }
    return array_merge(quicksort($less),$middle,quicksort($greater) );
}

function ArraySwap(&$arr,$l,$r) {
    $temp = $arr[$l];
    $arr[$l] = $arr[$r];
    $arr[$r] = $temp;
}

// O(1) space quicksort
function o1quicksortpartition(&$arr,$leftIndex,$rightIndex,$pivotIndex) {
    $checkVal = $arr[$pivotIndex];
    
    $storeIndex = $leftIndex;
    ArraySwap(&$arr,$pivotIndex,$rightIndex);
    
    for($i = $leftIndex; $i < $rightIndex; $i++) {
        if($arr[$i] <= $checkVal ) {
            ArraySwap(&$arr,$storeIndex,$i);
            $storeIndex++;
        }
    }
    ArraySwap(&$arr,$storeIndex,$rightIndex);
    return $storeIndex;
    
    
}

function o1_quicksort(&$arr,$leftIndex=null,$rightIndex=null) {
    if($leftIndex === null || $rightIndex=== null)
        return o1_quicksort($arr, 0, (count($arr)-1) );
        
    if($leftIndex < $rightIndex) {
        $pivot = (($leftIndex+$rightIndex)/2);
        $newPivetIndex = o1quicksortpartition(
            &$arr,
            $leftIndex,
            $rightIndex,
            $pivot
        );
        o1_quicksort(&$arr,$leftIndex,$newPivetIndex-1);
        o1_quicksort(&$arr,$newPivetIndex+1,$rightIndex);
    }
}

$answer1 = $testarr;
$answer2 = $testarr;
$answer3 = $testarr;

sort($answer1);
$answer2 = quicksort($answer2);
o1_quicksort(&$answer3);

echo "Question 2 $linebreak";
echo "         sort(".PrintArray($testarr).") = ".PrintArray($answer1).$linebreak;
echo "    quicksort(".PrintArray($testarr).") = ".PrintArray($answer2).$linebreak;
echo " 01_quicksort(".PrintArray($testarr).") = ".PrintArray($answer3).$linebreak;