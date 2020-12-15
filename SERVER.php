<?php

use Codedungeon\PHPCliColors\Color;
use Nette\Neon\Neon;

global $cb_route;
global $cb_registry;
global $cb_app;
global $config;
global $twig;

include_once "vendor/autoload.php";
include_once "Bin/Headers.php";
include_once "Bin/Router.php";
include_once "Bin/Server.php";

set_time_limit(0);

$twig = new \Twig\Environment(new \Twig\Loader\ArrayLoader([]));

$config = (object) Neon::decode((string) file_get_contents('./conf/config.yml'));
$cb_registry = (object) Neon::decode((string) file_get_contents('./conf/registry.yml'));
$cb_app = (object) Neon::decode((string) file_get_contents('./conf/app.yml'));
$cb_route = (object) Neon::decode((string) file_get_contents('./conf/route.yml'));

echo Color::GREEN, Color::BOLD, "The Cardboard server has successfully started up", Color::RESET, PHP_EOL;
echo Color::WHITE, Color::BOLD, "URL: http://{$config->server['ip']}:{$config->server['port']}", Color::RESET, PHP_EOL;
echo Color::WHITE, Color::BOLD, "IP: {$config->server['ip']}", Color::RESET, PHP_EOL;
echo Color::WHITE, Color::BOLD, "PORT: {$config->server['port']}", Color::RESET, PHP_EOL;

$SERVER = new Server($config->server['ip'], $config->server['port']);
$SERVER->listen();