<?php

use App\App;
use GuzzleHttp\Psr7\ServerRequest;

require "../vendor/autoload.php";
require '../configs/config.php';

// -----dev
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
// -----dev

$urls = require '../configs/routerPath.php';
$app = new App($urls); 
$response = $app->run(ServerRequest::fromGlobals());
Http\Response\send($response);