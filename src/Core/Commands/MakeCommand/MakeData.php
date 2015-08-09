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
     * Construct.
     * 
     * @param string $item
     */
    public function __construct($item)
    {
        $this->item = $item;
    }
}
