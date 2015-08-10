<?php

namespace Petrol\Core\Commands\FillCommand;

use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Console\AppNamespaceDetectorTrait;

class ArtisanFill extends Command
{
    use AppNamespaceDetectorTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'petrol:fill {name} {--errors}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill a database table with a Petrol Filler';


    /**
     * Construct.
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $root_namespace = $this->getAppNamespace();

        $file_namespace = $root_namespace.'Petrol\\Fillers\\Fill'.makeClassName($name);

        $errors = $this->option('errors');

        $config_path = getcwd().'/config/database.php';

        $source = 'artisan';

        $data = new FillData($file_namespace, $errors, $config_path, $source);

        $handler = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $handler->handle($data);
    }
}