<?php

/**
 * @class Persons_Dealer
 * There can be only one dealer.
 */
class Persons_Dealer extends Person {
    static private $static_singletonLock = false;
    private $m_deck;
    
    /**
     * Constructor
     */
    public function __construct(Deck $d) {
        $this->m_deck = $d;
        
        parent::__construct("The Dealer");
        if(self::$static_singletonLock)
            throw new Exception("There is already a dealer at this table");
        self::$static_singletonLock = true;
    }
    
    
    public function __destruct() {
        self::$static_singletonLock = false;
    }
    
    public function &Deck() {
        return $this->m_deck;
    }

}