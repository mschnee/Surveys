<?php

/**
 * @class Person
 * A Person is the base class for all Players and Dealers.
 */ 
class Person {
    protected $p_name = "";
    public function __construct($personName) {
        $this->p_name = $personName;
    }    
    
    public function Name() {
        return $this->p_name;
    }
}