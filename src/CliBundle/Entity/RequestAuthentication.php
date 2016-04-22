<?php

namespace CliBundle\Entity;

use Symfony\Component\HttpFoundation\Request;

class RequestAuthentication {
    protected $success = false;
    protected $message;

    private $_request;

    public function __construct($secret){
        $this->_request = Request::createFromGlobals();

        return $this->_validateKey($secret);
    }

    public function authenticated(){
        return $this->success;
    }

    public function getMessage(){
        return $this->message;
    }

    private function _validateKey($secret){
        $key = $this->_request->headers->get('x-vermillion-key');

        $this->success = ($key === sha1($secret));

        if(!$this->success){
            $this->message = "Invalid authentication key";
        }

        return $this;
    }
}

?>