<?php

namespace Petrol\Core\Commands\NewCommand;

class NewData
{
    /**
     * Name of database table.
     *
     * @var string
     */
    public $name;

    /**
     * Name of file to be parsed.
     *
     * @var string
     */
    public $file;

    /**
     * Namespace.
     *
     * @var string
     */
    public $namespace;

    /**
     * Path to Petrol folder.
     *
     * @var string
     */
    public $petrol_path;

    /**
     * Construct.
     *
     * @param string $name
     * @param string $file
     * @param string $namespace
     * @param string $petrol_path
     */
    public function __construct($name, $file, $namespace, $petrol_path)
    {
        $this->name = $name;
        $this->file = $file;
        $this->namespace = $namespace;
        $this->petrol_path = $petrol_path;
    }
}
