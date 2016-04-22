<?php

namespace CliBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class RequestAuthorization {
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

        if(version_compare(PHP_VERSION, '5.6.0') >= 0){
            $this->success = hash_equals($key, sha1($secret));
        }else {
            $this->success = ($key === sha1($secret));
        }

        if(!$this->success){
            $this->message = "Invalid authentication key";
        }

        return $this;
    }
}

?>