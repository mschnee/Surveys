<?php
/*
    Question 4: Your application consumes a web service that returns an array
    of data. The array contains 5 nodes of data in each element. Write a 
    function that would be able to consume the web service and display each 
    data set to a view. 
    
    ----------------------------------------------------------------------------
    It happens that I wrote a RESTful web service as part of a code challenge,
    and modified it slighly in order to return some relevant data for this
    qustion.
    
    I've implemented two solutions- one a bit more MVC, and one a bit more 
    procedural.  The latter could have used array_map.
    
    I pulled in quite a bit of code here from that other code challenge.
*/

$linebreak = PHP_SAPI=='cli'?"\n":"<br/>";

/**
 *  A VERY simple viewer class.
 */
class HandView {
    static $s_cardSuits = array(
        "Diamonds",
        "Clubs",
        "Hearts",
        "Spades"
    );
    static $s_cardLetters = array(
        "Two","Three","Four","Five","Six","Seven","Eight","Nine", "Ten",
        "Jack","Queen","King","Ace"
    );
    
    private $hand = null;
    public function __construct($cards) {
        $this->hand = $cards;
    }
    
    public function html() {
        $c = array();
        if(!$this->hand)
            return "Nothing";
        foreach($this->hand as $cardID) {
            $suit = ($cardID % 4);
            $card = ($cardID % 13);
            $c[] = self::$s_cardLetters[$card]." of ".self::$s_cardSuits[$suit];
        }
        
        return implode(",",$c);
    }
}

/**
 * Table "controller" 
 */
class Table {
    private $players = array();
    public function __construct($hands) {
        foreach($hands as $h) {
            $this->players[] = new HandView($h);
        }
    }
    
    public function html() {
        global $linebreak;
        $ret = "";
        foreach($this->players as $id=>$p) {
            $ret.="Player ".($id+1)." has ".$p->html().$linebreak;
        }
        return $ret;
    }
}

/**
 * The function equivalent of the above classes.
 */ 
function handleResponse($data) {
    global $linebreak;
    foreach($data as $hand) {
        $player = new HandView($hand);
        echo $player->html().$linebreak;
    }
}

/* fallback data if CURL fails */
$data = '{"success":true,"data":[[2,47,43,46,44],[37,3,1,25,18],[9,12,49,50,35],[6,29,26,21,30]]}';

try {
    $fp = curl_init("http://mschnee.twopitau.com/Projects/War/api.php/Dealer/dealHands/players=4&cards=5");
    curl_setopt($fp,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($fp, CURLOPT_HEADER, 0);
    $data = curl_exec($fp);
    curl_close($fp);
} catch (Exception $e) {

}
$data = json_decode($data,true);

/* the MVC solution */
$cardTable = new Table($data['data']);
echo $cardTable->html().$linebreak;

/* the functional solution */
handleResponse($data['data']);
echo $linebreak;
