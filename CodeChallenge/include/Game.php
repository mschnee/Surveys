<?php

/**
 * Interface for the game engine
 */
interface Game {
    public function addDealer(Persons_Dealer &$d);
    public function addPlayer(Persons_Player &$d);
    public function setTable(Table &$t);
    
    public function Start();
    public function Result();
}