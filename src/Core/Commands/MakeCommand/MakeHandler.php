<?php

namespace Petrol\Core\Commands\MakeCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeHandler
{
    private $input;

    private $output;

    /**
     * Construct.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * Handle request to make item.
     *
     * @param MakeData $data
     */
    public function handle(MakeData $data)
    {
        $class = $this->makeClassFactory($data->item);

        $class->execute($this->input, $this->output);
    }

    /**
     * Factory for make command classes.
     *
     * @param string $item
     *
     * @return Class
     */
    private function makeClassFactory($item)
    {
        $class_name = 'Make'.ucfirst($item);

        $class = 'Petrol\\Core\\Commands\\MakeCommand\\'.$class_name;

        if (!class_exists($class)) {
            throw new \Exception('Argument '.$item.' does not exist.');
        }

        return new $class();
    }
}
