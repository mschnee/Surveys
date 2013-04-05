<?php
/**
 * @class Table
 * The table is the front-controller for the code challenge.
 */
 
 class Table {
     private $m_dealer = null;
     private $m_players = array();
     private $m_deck;
     private $m_game = "War";
     static private $knownGames = array("War");
     
     private $m_gameObject = null;
     public function __construct($request) {
         if(isset($request['gameType']) and in_array($request['gameType'],$request)) {
             $m_game = $request['gameType'];
         }
         
         try {
             debug("making Decks_{$this->m_game}");
             $refDeck = new ReflectionClass("Decks_{$this->m_game}");
             $this->m_deck = $refDeck->newInstance();
             
             $refGame = new ReflectionClass("Games_{$this->m_game}");
             $this->m_gameObject = $refGame->newInstance();
             
             
             $this->m_gameObject->addDealer( new Persons_Dealer($this->m_deck) );
             $this->m_players[] = new Persons_Player("Player 1");
             $this->m_players[] = new Persons_Player("Player 2");
             $this->m_gameObject->addPlayer( $this->m_players[0] );
             $this->m_gameObject->addPlayer( $this->m_players[1] );
             $this->m_gameObject->setTable( $this );
         } catch(Exception $e) {
             debug($e->getMessage());
         }
         
     }
     
     
     public function logTurn($message) {
         echo $message;
     }
     
     public function __invoke() {
         debug("begining game");
         if($this->m_gameObject) {
             $this->m_gameObject->Start();
             $results = $this->m_gameObject->Result();
             $view = new Views_War($results,$this->m_deck,$this->m_players);
             echo $view->html();
         }
             
         
     }
     
     public function __toString() {
     
     }
 }