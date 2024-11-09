<?php

namespace App\Libraries;

use Exception;

class CustomException extends Exception {
    private $arrayMessage = null;
    public function __construct($message = null, Int $code = 0, Exception $previous = null){
        if(is_array($message)){
            $this->arrayMessage = $message;
            $message = null;
        }
        $this->exception = new Exception($message, $code, $previous);
    }
    public function getCustomMessage(): Array|String
    {
        return $this->arrayMessage ? $this->arrayMessage : $this->getMessage();
    }
}
