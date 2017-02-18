<?php

use Ratchet\Server\IoServer;
use Snake\App;

require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(new App, 8080);
$server->run();