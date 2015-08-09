<?php

namespace Petrol\Core\Commands\MakeCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleMake extends Command
{
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

        $data = new MakeData($item);

        $handler = new MakeHandler($input, $output);

        $handler->handle($data);
    }
}
