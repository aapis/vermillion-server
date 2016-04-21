<?php

    namespace CliBundle\Entity;

    class Json {
        protected $_message;
        protected $_data;
        protected $_title;

        public function getMessage(){
            return $this->_message;
        }

        public function getData(){
            return $this->_data;
        }

        public function getTitle(){
            return $this->_title;
        }

        public function setMessage($input){
            $this->_message = $input;
        }

        public function setData($input){
            $this->_data = $input;
        }

        public function setTitle($input){
            $this->_title = $input;
        }
    }

?>