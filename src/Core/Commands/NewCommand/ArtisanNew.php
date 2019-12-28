<?php

namespace Petrol\Core\Commands\NewCommand;

use Illuminate\Console\Command;
use Petrol\Core\Commands\NewCommand\NewData;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\ArgvInput;
use Illuminate\Console\DetectsApplicationNamespace;
use Symfony\Component\Console\Output\ConsoleOutput;

class ArtisanNew extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'petrol:new {name} {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new Petrol Filler';


    /**
     * Construct.
     */
    public function __construct()
    {
        parent::__construct();

        $this->filesystem = new Filesystem();
    }

    /**
     * Get data and pass to command handler.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $filler_path = getcwd().'/app/Petrol/Fillers/';

        $file = $this->option('file');

        $root_namespace = $this->getAppNamespace();

        $namespace = $root_namespace.'Petrol';

        $petrol_path = getcwd().'/vendor/zachleigh/petrol/';

        $data = new NewData($name, $filler_path, $file, $namespace, $petrol_path);

        $handler = new NewHandler(new ArgvInput(), new ConsoleOutput(), $this->filesystem);

        $handler->handle($data);
    }
}
