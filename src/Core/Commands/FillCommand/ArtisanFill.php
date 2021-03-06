<?php

namespace Petrol\Core\Commands\FillCommand;

use Illuminate\Console\Command;
use Petrol\Core\Helpers\Traits\Helpers;
use Symfony\Component\Console\Input\ArgvInput;
use Illuminate\Console\DetectsApplicationNamespace;
use Symfony\Component\Console\Output\ConsoleOutput;

class ArtisanFill extends Command
{
    use DetectsApplicationNamespace, Helpers;

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
     * Get data and pass to command handler.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $root_namespace = $this->getAppNamespace();

        $file_namespace = $root_namespace.'Petrol\\Fillers\\Fill'.$this->makeClassName($name);

        $errors = $this->option('errors');

        $config_path = getcwd().'/config/database.php';

        $source = 'artisan';

        $data = new FillData($file_namespace, $errors, $config_path, $source);

        $handler = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $handler->handle($data);
    }
}
