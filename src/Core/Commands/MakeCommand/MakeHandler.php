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
        $class = $this->makeClassFactory($data);

        $class->execute($this->input, $this->output);
    }

    /**
     * Factory for make command classes.
     *
     * @param MakeData $dat
     *
     * @return Class
     */
    private function makeClassFactory(MakeData $data)
    {
        $class_name = 'Make'.ucfirst($data->item);

        $class = $data->namespace.'\\Core\\Commands\\MakeCommand\\'.$class_name;

        var_dump($class);

        if (!class_exists($class)) {
            throw new \Exception('Argument '.$data->item.' does not exist.');
        }

        return new $class();
    }
}
