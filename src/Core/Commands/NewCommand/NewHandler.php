<?php

namespace Petrol\Core\Commands\NewCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class NewHandler
{
    private $input;

    private $output;

    private $filesystem;

    /**
     * Construct.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output, Filesystem $filesystem)
    {
        $this->input = $input;
        $this->output = $output;
        $this->filesystem = $filesystem;
    }

    /**
     * Handle request to make new filler.
     *
     * @param NewData $data
     */
    public function handle(NewData $data)
    {
        $class_name = 'Fill'.makeClassName($data->name);

        $this->validateClassName($class_name, $data);

        $this->validatePath($data->filler_path);

        $class_path = $data->filler_path.'/'.$class_name.'.php';

        $filler = $this->makeFiller($data, $class_name);

        file_put_contents($class_path, $filler);

        $this->validateWrite($class_path);

        $this->output->writeln('<info>Filler successfully created.</info>');
    }

    /**
     * Make filler from stub data.
     *
     * @param NewData $data
     * @param string  $class_name
     *
     * @return string
     */
    private function makeFiller(NewData $data, $class_name)
    {
        $stub_path = $data->petrol_path.'/src/Core/Stubs/FillerStub.php';

        $stub_data = file_get_contents($stub_path);

        $filler = $this->setClassAttributes($stub_data, $data, $class_name);

        return $filler;
    }

    /**
     * Set parser attributes.
     *
     * @param string $stub_data
     * @param string $name      [the database table name]
     * @param string $file      [file name, if set]
     *
     * @return string
     */
    private function setClassAttributes($stub_data, $data, $class_name)
    {
        $stub_data = $this->setNamespace($stub_data, $data->namespace);

        $stub_data = $this->setTableName($stub_data, $data->name);

        $stub_data = $this->setClassname($stub_data, $class_name);

        $stub_data = $this->setFileName($stub_data, $data->file);

        return $stub_data;
    }

    /**
     * Set parser classname.
     *
     * @param string $stub_data
     *
     * @return string
     */
    private function setClassname($stub_data, $class_name)
    {
        return str_replace('DummyClass', $class_name, $stub_data);
    }

    /**
     * Set file used by parser if given.
     *
     * @param string $stub_data
     * @param string $file
     *
     * @return string
     */
    private function setFileName($stub_data, $file)
    {
        if (empty($file)) {
            return str_replace(' = DummyFile', $file, $stub_data);
        }

        return str_replace('DummyFile', "'".$file."'", $stub_data);
    }

    /**
     * Set parser namespace.
     *
     * @param string $stub_data
     *
     * @return string
     */
    private function setNamespace($stub_data, $namespace)
    {
        return str_replace('DummyNamespace', $namespace.'\\Fillers', $stub_data);
    }

    /**
     * Set parser database table name.
     *
     * @param string $stub_data
     * @param string $name
     *
     * @return string
     */
    private function setTableName($stub_data, $name)
    {
        return str_replace('DummyTable', "'".$name."'", $stub_data);
    }

    /**
     * Throw exception if class/file already exists.
     *
     * @param string  $class_name
     * @param NewData $data
     */
    private function validateClassName($class_name, NewData $data)
    {
        if (class_exists($data->namespace.'\\Fillers\\'.$class_name) || $this->filesystem->exists($data->filler_path.'/'.$class_name.'.php')) {
            throw new \Exception('Filler for '.$data->name.' already exists.');
        }
    }

    /**
     * Throw exception if can not write to path.
     *
     * @param string $path
     */
    private function validatePath($path)
    {
        if (!is_writable($path)) {
            throw new \Exception('Can not write to Filler path.');
        }
    }

    /**
     * Validate that new filler exists. Throw exception if filler does not exist.
     *
     * @param string $class_path
     */
    private function validateWrite($class_path)
    {
        if (file_exists($class_path)) {
            return;
        }

        throw new \Exception('Filler could not be created.');
    }
}
