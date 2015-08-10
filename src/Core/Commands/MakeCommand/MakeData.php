<?php

namespace Petrol\Core\Commands\MakeCommand;

class MakeData
{
    /**
     * The item to make.
     * 
     * @var string
     */
    public $item;

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
     * @param string $item
     */
    public function __construct($item, $namespace, $petrol_path)
    {
        $this->item = $item;
        $this->namespace = $namespace;
        $this->petrol_path = $petrol_path;
    }
}
