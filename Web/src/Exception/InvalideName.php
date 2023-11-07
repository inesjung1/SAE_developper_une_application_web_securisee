<?php

namespace Iutncy\Sae\Exception;

class InvalideName extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}