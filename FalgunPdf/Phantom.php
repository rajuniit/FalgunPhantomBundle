<?php

namespace Falgun\Bundle\PhantomBundle\FalgunPdf;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader;

class Phantom
{
    protected $source,$configuration,$outfile;
    protected $options,$result,$error;
    protected $config;
    protected $scriptFile = '';
    protected $cookieFilePath = '';
    protected $phantom;

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

    public function to_pdf($url, $config = array(), $outfile = null)
    {
        $this->outfile = $outfile;
        $this->source = $url;
        $this->run();
        return $this->outfile;

    }

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
