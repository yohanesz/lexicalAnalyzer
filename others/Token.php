<?php

class Token {

    private $lexema;
    private $token;

   
    public function __construct($token, $lexema)
    {
        $this->token = $token;
        $this->lexema = $lexema;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function setLexema($lexema) {
        $this->lexema = $lexema;
    }

    public function getToken() {
        return $this->token;
    }

    public function getLexema() {
        return $this->lexema;
    }
  
    public function __toString()
    {
        return $this->token;
    }
    
}
?>
