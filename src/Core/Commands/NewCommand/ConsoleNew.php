<?php

namespace Petrol\Core\Commands\NewCommand;

use Petrol\Core\Commands\CommandTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ConsoleNew extends Command
{
    use CommandTrait;

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
        $this->setName('new')
             ->setDescription('Create a new database filler')
             ->addArgument('name', InputArgument::REQUIRED, 'The database table to be filled')
             ->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'The file for the filler to parse')
             ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'Path (For testing purposes)');
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

        $path = $input->getOption('path');

        $filler_path = $this->findFillerDirectory($path);

        $file = $input->getOption('file');

        $namespace = 'Petrol';

        $petrol_path = $this->findPetrolPath();

        $data = new NewData($name, $filler_path, $file, $namespace, $petrol_path);

        $handler = new NewHandler($input, $output, $this->filesystem);

        $handler->handle($data);
    }
}
