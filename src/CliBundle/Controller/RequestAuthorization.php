<?php

namespace CliBundle\Controller;

use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;

class RequestAuthorization {
    protected $success = false;
    protected $message;

    private $_request;
    private $_logger;

    public function __construct($secret, Logger $logger){
        $this->_request = Request::createFromGlobals();
        $this->_logger = $logger;

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
        $user = $this->_request->headers->get('from');

        $this->success = $this->_hash_equals($key, sha1($secret));

        // validate api key
        
        // validate user

        if(!$this->success){
            $this->message = "Invalid authentication key";
        }

        $this->_logger->info("Request received from ". $user ." (". $key .")");

        return $this;
    }

    /**
     * Cross-PHP version of hash_equals for versions <= 5.6
     * @from http://php.net/manual/en/function.hash-equals.php#115635
     */
    private function _hash_equals($str1, $str2){
        if(function_exists('hash_equals')){
            return hash_equals($str1, $str2);
        }else {
            if(strlen($str1) != strlen($str2)) {
              return false;
            } else {
              $res = $str1 ^ $str2;
              $ret = 0;
              for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
              return !$ret;
            }
        }
    }
}

?>