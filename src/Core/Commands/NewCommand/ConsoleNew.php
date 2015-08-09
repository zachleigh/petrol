<?php

namespace Petrol\Core\Commands\NewCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleNew extends Command
{
    /**
     * Configure command.
     */
    public function configure()
    {
        $this->setName('new')
             ->setDescription('Create a new database filler')
             ->addArgument('name', InputArgument::REQUIRED, 'The database table to be filled')
             ->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'The file for the filler to parse')
             ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'Path (For testing purposes)', getcwd().'/src/Fillers');
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

        $file = $input->getOption('file');

        $namespace = 'Petrol';

        $petrol_path = $input->getOption('path');

        $data = new NewData($name, $file, $namespace, $petrol_path);

        $handler = new NewHandler($input, $output);

        $handler->handle($data);
    }
}
