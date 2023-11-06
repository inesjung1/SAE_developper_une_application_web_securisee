<?php

namespace Iutncy\Sae\Exception;
class AuthException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
