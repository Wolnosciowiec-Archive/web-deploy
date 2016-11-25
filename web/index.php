<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Wolnosciowiec\WebDeploy\Kernel();

$app->addTask(new \Wolnosciowiec\WebDeploy\Tasks\PhinxMigrateTask());
$response = $app->handleRequest(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

(new Zend\Diactoros\Response\SapiEmitter)->emit($response);