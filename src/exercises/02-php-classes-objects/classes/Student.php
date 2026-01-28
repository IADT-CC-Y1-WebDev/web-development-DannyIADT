<?php
    class Student{
        protected $Name;
        protected $Number;

        public function __construct($Name, $Number){
            try{
                if(empty($Name) || empty($Number)){
                    throw new Exception("Missing student name or number");
                } 

                $this->Name = $Name;
                $this->Number = $Number;
            }

            catch(Exception $e){
                echo "Error: " . $e->getMessage() . "<br>";
            }

        }

        public function getName(){
            
            return $this->Name;
        }

        public function getNumber(){
            return $this->Number;
        }
    }
?>