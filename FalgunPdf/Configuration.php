<?php

/*
 * This file is part of the falgun phantom bundle.
 *
 * (c) rajuniit.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falgun\Bundle\PhantomBundle\FalgunPdf;

/**
 * Class Configuration
 * @package Falgun\Bundle\PhantomBundle\FalgunPdf
 */
class Configuration
{
    /**
     * @var array
     */
    protected $defaultConfig = array();



    public function __construct()
    {

        $this->defaultConfig = array(
            'format' => 'A4',
            'margin' => '1cm',
            'zoom'  => 1,
            'orientation' => 'portrait',
            'tmpdir'    => sys_get_temp_dir(),
            'rendering_time' => 1000,
            'rendering_timeout' => 9000,
            'viewport_width' => 600,
            'viewport_height' => 600,
            'command_config_file' => __DIR__. '/config.json'
        );
    }

    /**
     * @return array
     */
    public function getDefaultConfig()
    {
        return $this->defaultConfig;
    }
}