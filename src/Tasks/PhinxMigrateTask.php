<?php

namespace Wolnosciowiec\WebDeploy\Tasks;

use Phinx\Wrapper\TextWrapper;
use \Psr\Http\Message\RequestInterface;
use Wolnosciowiec\WebDeploy\Exceptions\DeploymentFailure;

/**
 * @package Wolnosciowiec\WebDeploy\Tasks
 */
class PhinxMigrateTask implements TaskInterface
{
    /**
     * @var string $configurationPath
     */
    protected $configurationPath = null;

    protected function getEnvironment()
    {
        return getenv('WL_PHINX_ENV') ? getenv('WL_PHINX_ENV') : null;
    }

    protected function getTarget()
    {
        return getenv('WL_PHINX_TARGET') ? getenv('WL_PHINX_TARGET') : null;
    }

    protected function getParser()
    {
        return getenv('WL_PHINX_PARSER') ? getenv('WL_PHINX_PARSER') : 'Yaml';
    }

    protected function getConfigurationPath()
    {
        if ($this->configurationPath === null) {
            foreach (array_filter([
                __DIR__ . '/../../../../../phinx.yml',
                __DIR__ . '/../../../../../phinx.php',
                __DIR__ . '/../../phinx.yml',
                __DIR__ . '/../../phinx.php',
                getenv('WL_PHINX_PATH')
            ]) as $path)
            {
                if (is_file($path)) {
                    $this->configurationPath = $path;
                    return $path;
                }
            }

            throw new \Exception('Cannot find a phinx.php configuration file');
        }

        return $this->configurationPath;
    }

    public function execute(RequestInterface $request): string
    {
        $app = require __DIR__ . '/../../vendor/robmorgan/phinx/app/phinx.php';
        $wrap = new TextWrapper($app);

        $wrap->setOption('configuration', $this->getConfigurationPath());
        $wrap->setOption('parser',        $this->getParser());

        $action = $wrap->getMigrate($this->getEnvironment(), $this->getTarget());

        if ($wrap->getExitCode() !== 0) {
            throw new DeploymentFailure('Phinx failed with a non-zero exit code, details: "' . $action . '"');
        }

        return $action;
    }

    /**
     * @param string $configurationPath
     * @return $this
     */
    public function setConfigurationPath(string $configurationPath)
    {
        $this->configurationPath = $configurationPath;
        return $this;
    }
}