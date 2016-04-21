<?php

    namespace CliBundle\Entity;

    class Json {
        protected $_message;
        protected $_data;
        protected $_title;
        protected $_code;

        public function getMessage(){
            return $this->_message;
        }

        public function getData(){
            return $this->_data;
        }

        public function getTitle(){
            return $this->_title;
        }

        public function getCode(){
            return $this->_code;
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

        public function setCode($input){
            $this->_code = $input;
        }
    }

?>