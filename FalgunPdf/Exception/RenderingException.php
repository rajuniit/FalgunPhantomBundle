<?php

namespace Falgun\Bundle\PhantomBundle\FalgunPdf\Exception;


class RenderingException extends \Exception
{
    public function __construct($msg = null)
    {
        parent::__construct("Rendering exception: " . $msg, 404);
    }
}