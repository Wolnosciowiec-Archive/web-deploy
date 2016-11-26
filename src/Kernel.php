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