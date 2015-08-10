<?php

namespace Petrol\Core\Commands\NewCommand;

class NewData
{
    /**
     * Name of database table, filler.
     *
     * @var string
     */
    public $name;

    /**
     * Full path to filler folder.
     * 
     * @var string
     */
    public $filler_path;

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
     * Full path to Petrol folder.
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
    public function __construct($name, $filler_path, $file, $namespace, $petrol_path)
    {
        $this->name = $name;
        $this->filler_path = $filler_path;
        $this->file = $file;
        $this->namespace = $namespace;
        $this->petrol_path = $petrol_path;
    }
}
