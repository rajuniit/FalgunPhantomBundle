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
 * Class ImproperSourceException
 * @package Falgun\Bundle\PhantomBundle\FalgunPdf\Exception
 */
class ImproperSourceException extends \Exception
{

    /**
     * @param null $msg
     */
    public function __construct($msg = null)
    {
        parent::__construct("Improper source exception: " . $msg, 404);
    }
}