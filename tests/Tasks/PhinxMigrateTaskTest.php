<?php

namespace Tests;

require_once __DIR__ . '/../AbstractTestCase.php';

use Wolnosciowiec\WebDeploy\Tasks\PhinxMigrateTask;
use \GuzzleHttp\Psr7\ServerRequest;

/**
 * @package Tests
 */
class PhinxMigrateTaskTest extends AbstractTestCase
{
    /**
     * @see PhinxMigrateTask
     */
    public function testInvalidEnvSelected()
    {
        putenv('WL_PHINX_ENV=invalid');

        $app = $this->getApp()
            ->addTask(new PhinxMigrateTask());

        $response = $app->handleRequest(ServerRequest::fromGlobals());
        $decoded  = json_decode((string)$response->getBody(), true);

        $this->assertFalse($decoded['success']);
        $this->assertSame('1_Wolnosciowiec\WebDeploy\Tasks\PhinxMigrateTask', $decoded['task_failed']);
        $this->assertContains('using environment invalid', $decoded['details']);
        $this->assertContains('Could not determine database name!', $decoded['details']);
    }

    /**
     * @see Kernel::handleRequest()
     */
    public function testValidMigrateAction()
    {
        putenv('WL_PHINX_ENV=default');

        $app = $this->getApp()
            ->addTask(new PhinxMigrateTask());

        $response = $app->handleRequest(ServerRequest::fromGlobals());
        $decoded  = json_decode((string)$response->getBody(), true);

        $this->assertTrue($decoded['success']);
        $this->assertArrayHasKey('1_Wolnosciowiec\\WebDeploy\\Tasks\\PhinxMigrateTask', $decoded['results']);
        $this->assertContains('All Done.', $decoded['results']['1_Wolnosciowiec\WebDeploy\Tasks\PhinxMigrateTask']);
    }
}