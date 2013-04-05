<?php

/**
 *  @class Views_War
 *  Renders the Game of War in HTML.
 *  This would be a trait in PHP 5.4, since it's more of a delegate class than
 *  an actual "View"
 */
class Views_War {
    private $data = null;
    function __construct($gamedata,$deck,$players=array()) {
        if($this->data) return;
        
        $d= "<table class=war><tr><th colspan=5>The game of war with ".count($players)."Players.</th></tr><tr>";
        $names = array();
        foreach($players as $p)
            $names[]= $p->Name();
        $d.= "<th>{$names[0]}</th><th>{$names[1]}</th><th>Winner</td><th colspan=2>Score</th></tr>";
        $d.= "<th colspan=3></th><th>1</th></th><th>2</th></tr>";
        $d .="</th></tr>";
        
        foreach($gamedata['log'] as $e) {
            if($e['action']=="war") {
                $d.= "<tr class=war><th colspan=5>WAR</th></tr>";
                foreach($e['plays'] as $p) {
                    $d.= "<tr class=war>
                    <td>{$deck->CardName($p['p1'])}</td>
                    <td>{$deck->CardName($p['p2'])}</td><td colspan=3></td></tr>";
                }
                $d.= "<tr class=war><td colspan=2></td><td>{$names[$e['result']]}</td><td>{$e['score'][0]}</td><td>{$e['score'][0]}</td></tr>";
                
            } else {
                $d.= "<tr class=play>
                    <td>{$deck->CardName($e['p1'])}</td>
                    <td>{$deck->CardName($e['p2'])}</td>
                    <td>{$names[$e['result']]}</td><td>{$e['score'][0]}</td><td>{$e['score'][0]}</td></tr>";
                
            }
        }
        $d.="</table>";
        $this->data = $d;
    }
    
    public function html() {
        $css = "<style type=\"text/css\">
            tr.war { background-color:#ffbbbb;}
            tr.war td {text-align:right; }
            span.red { color: red; }
        </style>";
        return $css.$this->data;
    }
}