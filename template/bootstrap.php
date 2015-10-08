<?php
require __DIR__ . '/vendor/autoload.php';
define('ROOT', __DIR__);

$phantom = \TestLib\PhantomJs::getInstance();
$mink = \TestLib\Mink::getInstance();
$mink->setUp($phantom->getPort());

