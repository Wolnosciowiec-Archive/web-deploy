<?php

namespace Tests;

require_once __DIR__ . '/AbstractTestCase.php';

use Wolnosciowiec\WebDeploy\Tasks\PhinxMigrateTask;
use \GuzzleHttp\Psr7\ServerRequest;

/**
 * @package Tests
 */
class KernelTest extends AbstractTestCase
{
    /**
     * @see Kernel::addTask()
     */
    public function testAddTask()
    {
        $this->assertCount(
            1,
            $this->getApp()
                ->addTask(new PhinxMigrateTask())
                    ->getTasks()
        );
    }

    /**
     * @see Kernel::handleRequest()
     */
    public function testHandleRequest()
    {
        putenv('WL_PHINX_ENV=default');

        $app = $this->getApp()
            ->addTask(new PhinxMigrateTask());

        $response = $app->handleRequest(ServerRequest::fromGlobals());
        $decoded  = json_decode((string)$response->getBody(), true);

        $this->assertTrue($decoded['success']);
        $this->assertArrayHasKey('1_Wolnosciowiec\\WebDeploy\\Tasks\\PhinxMigrateTask', $decoded['results']);
    }
}