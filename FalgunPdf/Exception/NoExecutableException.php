<?php

/*
 * This file is part of the falgun phantom bundle.
 *
 * (c) rajuniit.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falgun\Bundle\PhantomBundle\FalgunPdf\Exception;


/**
 * Class NoExecutableException
 * @package Falgun\Bundle\PhantomBundle\FalgunPdf\Exception
 */
class NoExecutableException extends \Exception
{

    public function __construct()
    {
        parent::__construct("No phantomjs executable found. Please install phantomjs", 404);
    }
}