Web Deploy
==========

Framework for creation of post-install scripts dedicated for applications hosted on shared hosting (without access to the shell).
Allows to execute scripts after installing the application on the destination FTP server.
Examples of usage: Clear the cache, migrate the application's database

Contains builtin tasks:
- PhinxMigrateTask

```
<?php

require __DIR__ . '/../vendor/autoload.php';

// add some authentication here, a token id verification?

$app = new \Wolnosciowiec\WebDeploy\Kernel();

$app->addTask(new \Wolnosciowiec\WebDeploy\Tasks\PhinxMigrateTask());
$response = $app->handleRequest(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

(new Zend\Diactoros\Response\SapiEmitter)->emit($response);
```