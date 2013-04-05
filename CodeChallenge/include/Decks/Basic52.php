<?php

class Decks_Basic52 extends Deck {
    private $m_cardSuits = array(
        "Diamonds",
        "Clubs",
        "Hearts",
        "Spades"
    );
    
    private $m_cardLetters = array(
        "Two","Three","Four","Five","Six","Seven","Eight","Nine", "Ten",
        "Jack","Queen","King","Ace"
    );
    
    public function __construct() {
        $this->m_cards = range(1,52);
    }
    
    /**
     *  Returns the name of a card.
     */
    public function CardName( $cardID=1 ) {
        $suit = ($cardID % 4);
        $card = ($cardID % 13);
        $color = ($suit%2)?"black":"red";
        debug(" $suit $card ");
        return "<span class=$color>{$this->m_cardLetters[$card]} of {$this->m_cardSuits[$suit]}</span>";
    }
    
    /**
     *  C-style comparison.
     *  @param int $leftCard the card on the left of (A>B)
     *  @param int $rightCard the card on the right of (A>B)
     *  @return int -1 is less than, 0 is equal to, 1 is greater than
     */
    public function Compare( $leftCard, $rightCard ) {
        $ls = $leftCard % 4;
        $ln = $leftCard % 13;
        $rs = $rightCard % 4;
        $rn = $rightCard % 13;
        
        if($ls==$rs)
            if($ln==$rn)
                return 0;
            else 
                return ($ln>$rn)?1:-1;
        else
            return ($ls>$rs)?1:-1;
    }
}