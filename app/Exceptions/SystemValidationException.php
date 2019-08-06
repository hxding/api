<?php
namespace App\Exceptions;

use Exception;

class SystemValidationException extends Exception
{
    public $http_code;
	public $message;

    public function __construct($http_code = 500, $message = "")
    {
        $this->http_code = $http_code;
        $this->message = $message;
    }
}