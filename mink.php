<?php

require __DIR__.'/vendor/autoload.php';

use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Session;
use Behat\Mink\Driver\Selenium2Driver;

//$driver = new GoutteDriver();
$driver = new Selenium2Driver();

$session = new Session($driver);
$session->start();

$session->stop();