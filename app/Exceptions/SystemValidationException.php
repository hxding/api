<?php
namespace App\Exceptions;

use Exception;

class SystemValidationException extends Exception
{
    public $code;
	public $message;

    public function __construct($code = 500, $message = "")
    {
        $this->code = $code;
        $this->message = $message;
    }

}