<?php

namespace Wolnosciowiec\WebDeploy;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Wolnosciowiec\WebDeploy\Exceptions\DeploymentFailure;
use Wolnosciowiec\WebDeploy\Tasks\TaskInterface;

/**
 * @package Wolnosciowiec\WebDeploy
 */
class Kernel
{
    /**
     * @var TaskInterface[] $tasks
     */
    private $tasks;

    /**
     * @param TaskInterface $task
     * @return Kernel
     */
    public function addTask(TaskInterface $task): Kernel
    {
        $this->tasks[] = $task;
        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handleRequest(RequestInterface $request)
    {
        $output = [];
        $num = 0;

        foreach ($this->tasks as $task) {
            $taskName = ++$num . '_' . get_class($task);

            try {
                $output[$taskName] = $task->execute($request);

            } catch (DeploymentFailure $e) {
                return new Response(
                    500, [
                    'Content-Type: application/json'
                ],
                    json_encode([
                        'success'     => false,
                        'message'     => 'Deployment failed',
                        'task_failed' => $taskName,
                        'details'     => $e->getMessage(),
                    ], JSON_PRETTY_PRINT)
                );
            }
        }

        return new Response(
            200, [
            'Content-Type: application/json'
        ],
            json_encode([
                'success' => true,
                'results' => $output,
            ], JSON_PRETTY_PRINT)
        );
    }
}