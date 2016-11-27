Web Deploy
==========

[![Build Status](https://travis-ci.org/Wolnosciowiec/web-deploy.svg?branch=master)](https://travis-ci.org/Wolnosciowiec/web-deploy)
[![Code quality](https://scrutinizer-ci.com/g/Wolnosciowiec/web-deploy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Wolnosciowiec/web-deploy/)
[![Coverage Status](https://coveralls.io/repos/github/Wolnosciowiec/web-deploy/badge.svg?branch=master)](https://coveralls.io/github/Wolnosciowiec/web-deploy?branch=master)

A Framework for creation of post-install scripts dedicated for applications hosted on shared hosting (without access to the shell).

Allows to execute scripts after installing the application on the destination FTP server.
Examples of usage: Clear the cache, migrate the application's database

Contains builtin tasks:
- PhinxMigrateTask

## Example of usage

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

// add some authentication here, a token id verification? ip address check?

$app = new \Wolnosciowiec\WebDeploy\Kernel();

// register tasks, pass parameters
$app->addTask(new \Wolnosciowiec\WebDeploy\Tasks\PhinxMigrateTask());

$response = $app->handleRequest(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

(new Zend\Diactoros\Response\SapiEmitter)->emit($response);
```