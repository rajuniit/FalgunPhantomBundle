<?php

namespace Falgun\Bundle\PhantomBundle\FalgunPdf\Exception;


class NoExecutableException extends \Exception
{
    public function __construct()
    {
        parent::__construct("No phantomjs executable found. Please install phantomjs", 404);
    }
}