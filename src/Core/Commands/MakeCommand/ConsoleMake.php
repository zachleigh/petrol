<?php

namespace Petrol\Core\Commands\MakeCommand;

use Petrol\Core\Commands\CommandTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ConsoleMake extends Command
{
    use CommandTrait;

    private $filesystem;

    /**
     * Construct
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
        $this->setName('make')
             ->setDescription('Make something')
             ->addArgument('item', InputArgument::REQUIRED, 'The item you want to make');
    }

    /**
     * Get data and pass to command handler.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $item = $input->getArgument('item');

        $namespace = 'Petrol';

        $petrol_path = $this->findPetrolPath();

        $data = new MakeData($item, $namespace, $petrol_path);

        $handler = new MakeHandler($input, $output);

        $handler->handle($data);
    }
}
