<?php

namespace Wolnosciowiec\WebDeploy\Tasks;

/**
 * @package Wolnosciowiec\WebDeploy\Tasks
 */
interface TaskInterface
{
    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return string
     */
    public function execute(\Psr\Http\Message\RequestInterface $request): string;
}