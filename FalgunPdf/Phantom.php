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


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class Phantom
 * @package Falgun\Bundle\PhantomBundle\FalgunPdf
 */
class Phantom
{
    /**
     * @var string
     */
    protected $source;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var string
     */
    protected $outfile;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var string
     */
    protected $result;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $scriptFile = '';

    /**
     * @var string
     */
    protected $cookieFilePath = '';

    /**
     * @var string
     */
    protected $phantom;


    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $configuration = new Configuration();
        $config = $configuration->getDefaultConfig();
        $this->config = array_merge($config, $container->getParameter('falgun_pdf.config'));

        $this->phantom = exec('which phantomjs');

        if(empty($this->config['phantomjs'])) {
            $this->config['phantomjs'] = $this->phantom;
        }

        $this->scriptFile = __DIR__. '/rasterize.js';
    }

    public function run()
    {
        $command = $this->getCommand();
        $this->result = `{$command}`;
    }

    /**
     * @return string
     */
    protected function getCommand()
    {
        $this->dumpCookies();
        $format = $this->config['format'];
        $zoom = $this->config['zoom'];
        $orientation = $this->config['orientation'];
        $margin = $this->config['margin'];

        $rendering_time = $this->config['rendering_time'];
        $rendering_timeout = $this->config['rendering_timeout'];

        $viewport_width = $this->config['viewport_width'];
        $viewport_height = $this->config['viewport_height'];

        if(empty($this->outfile)) {
            $this->outfile = $this->config['tmpdir'].'/'.$this->generateRandomNumber(). '.pdf';
        }

        $command_config_file = $this->config['command_config_file'];
        $command_config_file = "--config={$command_config_file}";
        $phantomjs = $this->config['phantomjs'];

        $command = $phantomjs . " ". $command_config_file. " ".
            $this->scriptFile. " ". $this->source. " ".
            $this->outfile. " ". $format. " ". $zoom. " ".
            $margin. " ". $orientation. " " .$this->cookieFilePath. " ". $rendering_time. " ".
            $rendering_timeout. " ". $viewport_width. " ".
            $viewport_height;

        return $command;
    }

    /**
     * @param $url
     * @param array $config
     * @param null $outfile
     * @return null
     */
    public function to_pdf($url, $config = array(), $outfile = null)
    {
        $this->outfile = $outfile;
        $this->source = $url;
        $this->run();
        return $this->outfile;

    }

    /**
     * @return string
     */
    private function generateRandomNumber()
    {
        $s = strtoupper(md5(uniqid(rand(),true)));
        $guidText =
            substr($s,0,8) . '-' .
            substr($s,8,4) . '-' .
            substr($s,12,4). '-' .
            substr($s,16,4). '-' .
            substr($s,20);
        return $guidText;
    }


    private function dumpCookies()
    {

        $value = isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID']: '';
        $data = array(
            'domain' => $_SERVER['HTTP_HOST'],
            'name' => "PHPSESSID",
            'value' => $value,
            'path' => "/",
            'httponly' => true,
            'secure' => false
        );


        $json_data = json_encode($data);
        $this->cookieFilePath = $this->config['tmpdir'].'/'.$this->generateRandomNumber(). '.cookies';
        $handler = fopen($this->cookieFilePath, "w+");
        fwrite($handler, $json_data);
        fclose($handler);


    }
}
