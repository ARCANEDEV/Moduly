<?php namespace Arcanedev\Moduly\Handlers;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Bases\Handler;

/**
 * Class     ModuleMakeHandler
 *
 * @package  Arcanedev\Moduly\Handlers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ModuleMakeHandler extends Handler
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $name;

    /**
     * Module files to be created.
     *
     * @var array
     */
    protected $files = [
        'src/Seeds/{{name}}DatabaseSeeder.php',
        'src/Http/routes.php',
        'src/Providers/{{name}}ServiceProvider.php',
        'src/Providers/RouteServiceProvider.php',
        'composer.json',
        'module.json',
    ];

    /**
     * Module stubs used to populate defined files.
     *
     * @var array
     */
    protected $stubs = [
        'seeder.stub',
        'routes.stub',
        'module-service-provider.stub',
        'route-service-provider.stub',
        'composer.stub',
        'module.stub',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the module slug
     *
     * @param  string  $slug
     *
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = str_slug($slug);
        $this->name = studly_case($this->slug);

        return $this;
    }

    /**
     * Get module folders
     *
     * @return array
     */
    public function getFolders()
    {
        return array_values(config('moduly.modules.folders', []));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Fire off the handler.
     *
     * @param  Command  $command
     * @param  string   $slug
     */
    public function fire(Command $command, $slug)
    {
        $this->setCommand($command);
        $this->setSlug($slug);

        if ( ! $this->moduly->exists($this->slug)) {
            $this->generate($command);
        }
        else {
            $command->comment("Module [{$this->name}] already exists.");
        }
    }

    /**
     * Generate module folders and files.
     *
     * @param  Command  $command
     *
     * @return bool
     */
    public function generate(Command $command)
    {
        $this->generateFolders();
        $this->generateGitkeep();
        $this->generateFiles();

        $command->info("Module [{$this->name}] has been created successfully.");

        $this->optimize($command);

        return true;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Generate defined module folders.
     */
    protected function generateFolders()
    {
        $baseFolder = $this->moduly->getBasePath();

        if ( ! $this->finder->isDirectory($baseFolder)) {
            $this->finder->makeDirectory($baseFolder);
        }

        $path = $this->getModulePath($this->slug);

        $this->finder->makeDirectory($path);

        foreach ($this->getFolders() as $folder) {
            $this->finder->makeDirectory($path . $folder, 0755, true);
        }
    }

    /**
     * Generate defined module files.
     */
    protected function generateFiles()
    {
        foreach ($this->files as $key => $file) {
            $file = $this->formatContent($file);

            $this->makeFile($key, $file);
        }
    }

    /**
     * Optimize the framework for better performance.
     *
     * @param  Command  $command
     */
    protected function optimize(Command $command)
    {
        $command->call('optimize');
    }

    /**
     * Generate .gitkeep files within generated folders.
     *
     * @return null
     */
    protected function generateGitkeep()
    {
        $modulePath = $this->getModulePath($this->slug);

        foreach ($this->getFolders() as $folder) {
            $this->finder->put($modulePath . $folder . '/.gitkeep', '');
        }
    }

    /**
     * Create module file.
     *
     * @param  int     $key
     * @param  string  $file
     *
     * @return int
     */
    protected function makeFile($key, $file)
    {
        return $this->finder->put(
            $this->getDestinationFile($file),
            $this->getStubContent($key)
        );
    }

    /**
     * Get the path to the module.
     *
     * @param  string  $slug
     *
     * @return string
     */
    protected function getModulePath($slug = null)
    {
        return $slug
            ? $this->moduly->getModulePath($slug)
            : $this->moduly->getBasePath();
    }

    /**
     * Get destination file.
     *
     * @param  string  $file
     *
     * @return string
     */
    protected function getDestinationFile($file)
    {
        return $this->getModulePath($this->slug) . $this->formatContent($file);
    }

    /**
     * Get stub content by key.
     *
     * @param  int  $key
     *
     * @return string
     */
    protected function getStubContent($key)
    {
        return $this->formatContent(
            $this->finder->get(__DIR__ . '/../../stubs/' . $this->stubs[$key])
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
            ['{{slug}}', '{{name}}', '{{namespace}}'],
            [$this->slug, $this->name, $this->moduly->getNamespace()],
            $content
        );
    }
}
