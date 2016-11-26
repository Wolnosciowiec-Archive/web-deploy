<?php

/*
 * Wolnościowiec / WebDeploy
 * ------------------------
 *
 *   Framework for creation of post-install scripts dedicated
 *   for applications hosted on shared hosting (without access to the shell).
 *
 *   A part of an anarchist portal - wolnosciowiec.net
 *
 *   Wolnościowiec is a project to integrate the movement
 *   of people who strive to build a society based on
 *   solidarity, freedom, equality with a respect for
 *   individual and cooperation of each other.
 *
 *   We support human rights, animal rights, feminism,
 *   anti-capitalism (taking over the production by workers),
 *   anti-racism, and internationalism. We negate
 *   the political fight and politicians at all.
 *
 *   http://wolnosciowiec.net/en
 *
 *   License: LGPLv3
 */

require __DIR__ . '/../vendor/autoload.php';

$app = new \Wolnosciowiec\WebDeploy\Kernel();

$app->addTask(new \Wolnosciowiec\WebDeploy\Tasks\PhinxMigrateTask());
$response = $app->handleRequest(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

(new Zend\Diactoros\Response\SapiEmitter)->emit($response);