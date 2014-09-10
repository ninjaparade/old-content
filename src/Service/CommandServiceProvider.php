<?php namespace Ninjaparade\Content\Service;

//use Illuminate\Filesystem\Filesystem;
//use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\ServiceProvider;
use Ninjaparade\Content\Commands\ViewCreatorCommand;

class CommandServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Booting
     */
    public function boot()
    {

    }

    /**
     * Register the commands
     *
     * @return void
     */
    public function register()
    {
        foreach(['Creator'] as $command)
        {
            $this->{"register$command"}();
        }
    }

    protected function registerCreator()
    {
        $this->app['ninjaparade:create'] = $this->app->share(function($app)
        {

            return new ViewCreatorCommand( new \Illuminate\Filesystem\Filesystem );
        });

        $this->commands('ninjaparade:create');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}

