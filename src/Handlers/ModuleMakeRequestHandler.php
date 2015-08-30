<?php namespace Arcanedev\Moduly\Handlers;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Bases\Handler;

/**
 * Class ModuleMakeRequestHandler
 * @package Arcanedev\Moduly\Handlers
 */
class ModuleMakeRequestHandler extends Handler
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name of the module
     *
     * @var string
     */
    protected $moduleName;

    /**
     * The name of the request class
     *
     * @var string
     */
    protected $className;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Fire off the handler.
     *
     * @param  Command  $command
     * @param  string   $slug
     * @param  string   $class
     *
     * @return bool
     */
    public function fire(Command $command, $slug, $class)
    {
        $this->command       = $command;
        $this->moduleName    = studly_case($slug);
        $this->className     = studly_case($class);

        if ( ! $this->moduly->exists($this->moduleName)) {
            $this->command->info("Module [$this->moduleName] does not exist.");

            return;
        }

        $this->makeFile();
        $this->command->info("Created Module Form Request: [$this->moduleName] " . $this->getFilename());
    }

    /**
     * Create new migration file.
     *
     * @return int
     */
    protected function makeFile()
    {
        return $this->finder->put(
            $this->getDestinationFile(),
            $this->getStubContent()
        );
    }

    /**
     * Get file destination.
     *
     * @return string
     */
    protected function getDestinationFile()
    {
        return $this->getPath() . $this->formatContent($this->getFilename());
    }

    /**
     * Get module migration path.
     *
     * @return string
     */
    protected function getPath()
    {
        $path = $this->moduly->getModulePath($this->moduleName);

        return $path . config('moduly.folders.requests');
    }

    /**
     * Get migration filename.
     *
     * @return string
     */
    protected function getFilename()
    {
        return $this->className . '.php';
    }

    /**
     * Get stub content.
     *
     * @return string
     */
    protected function getStubContent()
    {
        return $this->formatContent(
            $this->finder->get(__DIR__ . '/../../stubs/request.stub')
        );
    }

    /**
     * Replace placeholder text with correct values.
     *
     * @param  string  $content
     *
     * @return string
     */
    protected function formatContent($content)
    {
        return str_replace(
            ['{{className}}', '{{moduleName}}', '{{namespace}}'],
            [$this->className, $this->moduleName, $this->moduly->getNamespace()],
            $content
        );
    }
}
