<?php

namespace Tests;

use Wolnosciowiec\WebDeploy\Kernel;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Kernel
     */
    protected function getApp()
    {
        return new Kernel();
    }
}