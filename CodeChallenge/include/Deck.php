<?php

abstract class Deck implements Countable {
    protected $m_cards = array();
    
    private $m_cardPointer = 0;
    
    abstract public function CardName( $cardID=1 );
    abstract public function Compare( $leftCardID, $rightCardID );
    
    public function count() {
        return count($this->m_cards);
    }
        
    /**
     *  Shuffles the deck.
     *  Assume cards are returned.
     */
    public function Shuffle() {
        shuffle($this->m_cards);
    }
    
    
    /**
     * Cuts the deck
     */
    public function Cut( $cutAt=0 ) {
        if($cutAt < 0 or $cutAt >=count($this->m_cards))
            throw new OutOfBoundsException("You can only cut cards in the deck.");
        if(!$cutAt)
            return;
        /* cut the deck */
        $this->m_cards = array_slice($this->m_cards,0,$cutAt,true)+
                        array_slice($this->m_cards,$cutAt,count($this->m_cards),true);
    }
    
    /** 
     * Deals the specified number of cards off the top of the deck
     *  TODO: check math
     */
    public function getCards( $handSize=26 ) {
        if($handSize+$this->m_cardPointer > count($this))
            throw new OutOfRangeException("There aren't enough cards left to deal.");
        
        
        $ret =  array_slice($this->m_cards,$this->m_cardPointer,$handSize);
        $this->m_cardPointer+=$handSize;
        
        return $ret;
    }
}