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
        $ua = $this->_request->headers->get('user-agent');

        $this->message = "Access denied";

        // validate api key
        if(false === is_null($key)){
            $valid_key = $this->_hash_equals($key, hash('sha256', $secret));

            if($valid_key){
                // validate user
                // TODO: future feature, for now just having the FROM field
                //       populated is enough
                // - pull user info from database, see if they are allowed to
                //   use the provided key
                //   - if so, $this->success == true
                if(false === is_null($user)){
                    $valid_user = true;

                    if($valid_user){
                        $this->success = true;
                        $this->message = "User is authorized";
                    }else {
                        $this->message = "Invalid user";
                    }
                }else {
                    // default username so logs are easier to parse
                    $user = 'ANONYMOUS_USER';
                }
            }else {
                $this->message = "Invalid authentication key";
            }
        }else {
            $this->message = "Invalid authentication key";
        }

        // $this->_logger->info("Request received from {$user} ({$key}), using client {$ua},  with message {$this->message}");
        $this->_logger->info("- {$user}");
        $this->_logger->info("-- {$key}");
        $this->_logger->info("-- {$ua}");
        $this->_logger->info("-- {$this->message}");

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