<?php

class Connection extends Mysqli{

    private $host = 'localhost'; 
    private $user = 'root'; 
    private $password = "";
    private $database = 'api_rest';

    function __construct()
    {
        parent::__construct($this->host, 
                            $this->user, 
                            $this->password,
                            $this->database );

        $this->set_charset('utf8mb4');
    } // end construct
} // end connection

?>