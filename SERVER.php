<?php

use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\PHPConsoleHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

global $cb_route;
global $twig;
global $log;
global $config;

include_once "vendor/autoload.php";
include_once "Bin/Config.php";
include_once "Bin/Headers.php";
include_once "Bin/Router.php";
include_once "Bin/Server.php";

set_time_limit(0);

$log = new Logger('Main');

$log->pushHandler(new StreamHandler('logger.log'));
$log->pushHandler(new FirePHPHandler());
$log->pushHandler(new PHPConsoleHandler());

set_exception_handler(function ($exception) {
    global $log;
    $log->warning($exception->getMessage());
});

set_error_handler(function ($errno, $errstr,  $errfile, $errline, $errcontext) {
    global $log;
    $log->error($errstr);
});


$twig   = new \Twig\Environment(new \Twig\Loader\ArrayLoader([]));
$config = new Config();

$SERVER = new Server($config->config['server']['ip'], $config->config['server']['port']);

echo $SERVER->listen();