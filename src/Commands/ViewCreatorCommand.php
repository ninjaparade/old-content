<?php namespace Ninjaparade\Content\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Config;
use Theme;
use File;

class ViewCreatorCommand extends Command {

    protected $name = 'ninjaparade:content';

    protected $description = "Creates view templates for custom post types";

    /*
     * \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    public function __construct($files)
    {
        $this->files = $files;

        parent::__construct();
    }

    public function fire()
    {
        $theme = Theme::getCascadedPackageViewPaths('ninjaparade/content')[0];

        $this->info( $this->getIndexStub( $theme ));

        $this->info( $this->getSingleStub( $theme ));

    }

    protected function getIndexStub($theme)
    {

        $contents = file_get_contents( __DIR__.'/stubs/index.stub' );

        $name =$this->argument('name'). '-test-index.blade.php';

        $path = $theme .'/'. $name;

        file_put_contents($path, $contents);

        $this->info('view created at '. $path);

    }

    protected function getSingleStub($theme)
    {
        $contents = file_get_contents( __DIR__.'/stubs/single.stub' );

        $name =$this->argument('name'). '-test-single.blade.php';

        $path = $theme .'/'. $name;

        file_put_contents($path, $contents);

        $this->info('view created at '. $path);
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the post type']
        ];
    }

} 