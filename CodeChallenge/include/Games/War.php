<?php

/**
 * @class Games_War
 * Game controller for the game of "War"
 */
class Games_War implements Game {
    private $m_dealer = null;
    private $m_players = array();
    private $r_table = null;
    private $m_results = array();
    
    
    public function addDealer(Persons_Dealer &$d) {
        $this->m_dealer = $d;
    }
    public function addPlayer(Persons_Player &$d) {
        $this->m_players[] = $d;
    }
    public function setTable(Table &$t) {
        $this->r_table = $t;
    }
    
    /* I'd have made this a trait in 5.4 */
    public static function process($p1Cards = array(), $p2Cards=array(),Deck &$d=null) {
        $ret = array();
        $log = array();
        $winner = null;
        $p1Score = 0;
        $p2Score = 0;
        // the loser condition 
        while($winner === null) {
            $turn = array();
            $nextToShuffle = null;
            $p1count = count($p1Cards);
            $p2count = count($p2Cards);
            
            if($p1count<$p2count) {
                $nextToShuffle = 1;
            } elseif ($p1count<$p2count) {
                $nextToShuffle = 2;
            }
            $tc = min($p1count,$p2count);
            $ti = 0;
            
            while($ti < $tc) {
                $ti++;
                $l = array_shift($p1Cards);
                $r = array_shift($p2Cards);
                $c = $d->Compare($l,$r);
                $logEntry = array('type'=>"play","p1"=>$l,"p2"=>$r, 'action'=>'none','result'=>null,'score'=>array(0,0));
                $trick = array($l,$r);
                if($c==0) {
                    $logEntry['action']="war";
                    $logEntry['plays'] = array();
                    $trick = array();
                    while($c==0 and $ti<$tc) {
                        $ti++;
                        $l = array_shift($p1Cards);
                        $r = array_shift($p2Cards);
                        $logEntry['plays'][] = array('p1'=>$l,'p2'=>$r);
                        $trick += array($l,$r);
                        $c = $d->Compare($l,$r);
                    }
                    if($c>0) {
                        $p1Cards+=$trick;
                        $logEntry['result']=0;
                        
                    } else {
                        $p2Cards+=$trick;
                        $logEntry['result']=1;
                    }
                    
                    
                } elseif($c>0) {
                    $p1Cards+=array($l,$r);
                    $logEntry['result']=0;
                    
                } else {
                    $p2Cards+=array($l,$r);
                    $logEntry['result']=1;
                }
                $logEntry['receives'] = $trick;
                $log[]=$logEntry;
            }
            if(count($p1Cards)==0)
                $winner=2;
            else if(count($p2Cards)==0)
                $winner=1;
            else {
                if($nextToShuffle===null) {
                    shuffle($p1Cards);
                    shuffle($p2Cards);
                } else {
                    $h = "p{$nextToShuffle}Cards";
                    shuffle($hh);
                }
            }
        }
        return array(
            'winner'=>$winner,
            'log'=>$log
        );
        debug($log);
        
    }
    
    /*
        For the game of WAR:
        First, the dealer deals 26 cards to each player.
        Then, the players keep putting down cards.
        
    */
    public function Start() {
        $p1 = &$this->m_players[0];
        $p2 = &$this->m_players[1];
        $d = &$this->m_dealer;
        
        
        $deck = &$d->Deck();
        
        $deck->Shuffle();
        // dealer hands out.
        $cointoss = rand(0,1);
        $this->r_table->logTurn(
            $this->m_players[$cointoss]->Name()." wins the coin toss and cuts the deck at ". 
            $this->m_players[$cointoss]->cut( $d->Deck() ). "card(s) in." 
        );
        
        
        $p1->handCards( $deck->getCards(26) );
        $p2->handCards( $deck->getCards(26) );
        
        $playStack = array();
        
        $this->m_results = $this->process($p1->Hand(),$p2->Hand(),$deck);
        
    }
    public function Result() {
        return $this->m_results;
    }
}