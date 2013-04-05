<?php
class Persons_Player extends Person {
    private $m_score;
    private $m_hand;
    
    public function __construct($playerName) {
        parent::__construct($playerName);
    }
    
    /**
     *  Hands the deck to the player to cut.
     */
    public function cut(Deck &$d) {
        $at = rand(1,count($d)-1);
        $d->Cut( $at );
        return $at;
    }
    
    public function handCards( array $cards ) {
        $this->m_hand = $cards;
    }
    
    public function playCard() {
        $c = current($this->m_hand);
        next($this->m_hand);
        return $c;
    }
    
    public function Hand() {
        return $this->m_hand;
    }
    
}