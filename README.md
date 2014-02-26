## FalgunPhantomBundle

A symfony 2 bundle to generate pdf using phantomjs.

## Installation
----------------------------------------------------------------------------

##### Step 1: You need to download Phantomjs in your machine to use the bundle

Install phantomjs 1.9.2 from this link http://phantomjs.org/download.html


##### Step 2: Download FalgunPhantomBundle

Add FalgunPhantomBundle in your composer.json as below:

```
"falgun/phantom-bundle": "dev-master"

```

Update/install with this command:

```
php composer.phar update "falgun/phantom-bundle"
```

##### Step 2:  Enable the bundle

Register the bundle

```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Falgun\Bundle\PhantomBundle\FalgunPhantomBundle(),
);
```

##### Step 3:  Activate the main configs

```
# app/config/config.yml
falgun_pdf:
  config:
    format: 'A3'
    margin: '.5cm'
    zoom: 1
    orientation: 'portrait'
    rendering_time: 1000
    viewport_width: 800
    viewport_height: 800
    rendering_timeout: 90000
    phantomjs: '/usr/local/bin/phantomjs'
```

## How to use ?


```php
public function indexAction()
{
    $falgun_pdf = $this->get('falgun_pdf');
    $result = $falgun_pdf->to_pdf("www.google.com");
}

```