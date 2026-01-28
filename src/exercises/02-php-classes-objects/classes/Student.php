<?php
    class Student{
        public $Name;
        public $Number;

        public function __construct($Name, $Number){
            $this->Name = $Name;
            $this->Number = $Number;
        }

        public function getName(){
            return $this->Name;
        }

        public function getNumber(){
            return $this->Number;
        }
    }
?>