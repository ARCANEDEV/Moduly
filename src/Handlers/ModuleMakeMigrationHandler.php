<?php namespace Arcanedev\Moduly\Handlers;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Bases\Handler;

/**
 * Class ModuleMakeMigrationHandler
 * @package Arcanedev\Moduly\Handlers
 */
class ModuleMakeMigrationHandler extends Handler
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var string $moduleName The name of the module
     */
    protected $moduleName;

    /**
     * @var string $table The name of the table
     */
    protected $table;

    /**
     * @var string $migrationName The name of the migration
     */
    protected $migrationName;

    /**
     * @var string $className The name of the migration class
     */
    protected $className;

    /* ------------------------------------------------------------------------------------------------
     |  Getter & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set module name
     *
     * @param  string  $moduleName
     *
     * @return $this
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = studly_case($moduleName);

        return $this;
    }

    /**
     * Set table name
     *
     * @param  string  $table
     *
     * @return self
     */
    private function setTable($table)
    {
        $this->table         = strtolower($table);
        $this->migrationName = snake_case($this->table);
        $this->className     = studly_case($this->migrationName);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Fire off the handler.
     *
     * @param  Command $command
     * @param  string  $module
     * @param  string  $table
     */
    public function fire(Command $command, $module, $table)
    {
        $this->command      = $command;
        $this->setModuleName($module);
        $this->setTable($table);

        if ( ! $this->moduly->exists($this->moduleName)) {
            $this->command->info("Module [$this->moduleName] does not exist.");

            return;
        }

        $this->makeFile();
        $this->command->info("Created Module Migration: [$this->moduleName] " . $this->getFilename());

        chdir(app('path.base'));
        exec('composer dump-autoload');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
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

        return $path . config('moduly.folders.migrations');
    }

    /**
     * Get migration filename.
     *
     * @return string
     */
    protected function getFilename()
    {
        return date("Y_m_d_His") . '_' . $this->migrationName . '.php';
    }

    /**
     * Get stub content.
     *
     * @return string
     */
    protected function getStubContent()
    {
        return $this->formatContent(
            $this->finder->get(__DIR__ . '/../../stubs/migration.stub')
        );
    }

    /**
     * Replace placeholder text with correct values.
     *
     * @param  string $content
     * @return string
     */
    protected function formatContent($content)
    {
        return str_replace(
            ['{{migrationName}}', '{{table}}'],
            [$this->className, $this->table],
            $content
        );
    }
}
