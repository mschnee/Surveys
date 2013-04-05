<?php

class Decks_War extends Decks_Basic52 {   
    /**
     *  C-style comparison.
     *  @param int $leftCard the card on the left of (A>B)
     *  @param int $rightCard the card on the right of (A>B)
     *  @return int -1 is less than, 0 is equal to, 1 is greater than
     */
    public function Compare( $leftCard, $rightCard ) {
        $l = $leftCard % 13;
        $r = $rightCard % 13;
        
        if($l==$r)
            return 0;
            
        else 
            return ($l>$r)?1:-1;
    }
}