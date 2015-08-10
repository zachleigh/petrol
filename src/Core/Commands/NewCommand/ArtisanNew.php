<?php

namespace Petrol\Core\Commands\NewCommand;

use Illuminate\Console\Command;
use Petrol\Core\Commands\NewCommand\NewData;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;

class ArtisanNew extends Command
{
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $filler_path = getcwd().'/database/Petrol/Fillers/';

        $file = $this->option('file');

        $namespace = 'Petrol';

        $petrol_path = getcwd().'/vendor/zachleigh/petrol/';

        $data = new NewData($name, $filler_path, $file, $namespace, $petrol_path);
        
        $handler = new NewHandler(new ArgvInput(), new ConsoleOutput());

        $handler->handle($data);
    }
}
