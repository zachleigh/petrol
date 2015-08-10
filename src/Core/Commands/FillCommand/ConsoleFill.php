<?php

namespace Petrol\Core\Commands\FillCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ConsoleFill extends Command
{
    private $filesystem;

    /**
     * Construct.
     *
     * @param Filesystem $filesystem [description]
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Configure command.
     */
    public function configure()
    {
        $this->setName('fill')
             ->setDescription('Parse file and fill database')
             ->addArgument('name', InputArgument::REQUIRED, 'The database table name to be filled')
             ->addOption('errors', 'e', InputOption::VALUE_NONE, 'Display full database errors')
             ->addOption('quiet', 'q', InputOption::VALUE_NONE, 'Disable all input prompts');
    }

    /**
     * Get data and pass to command handler.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $file_namespace = 'Petrol\\Fillers\\Fill'.makeClassName($name);

        $errors = $input->getOption('errors');

        $config_path = 'config.php';

        if ($input->getOption('quiet')) {
            $source = 'test';
        } else {
            $source = 'console';
        }

        $data = new FillData($file_namespace, $errors, $config_path, $source);

        $handler = new FillHandler($input, $output);

        $handler->handle($data);
    }
}
