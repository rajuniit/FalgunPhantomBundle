<?php

namespace Falgun\Bundle\PhantomBundle\FalgunPdf\Exception;


class ImproperSourceException extends \Exception
{
    public function __construct($msg = null)
    {
        parent::__construct("Improper source exception: " . $msg, 404);
    }
}