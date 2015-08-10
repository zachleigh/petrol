<?php

namespace Petrol\Tests\files;

use Petrol\Core\Contracts\Filler;
use Petrol\Core\Database\Connection;

class FillerWrongFill extends Filler
{
    /**
     * Database connection class.
     * 
     * @var     Connection
     */
    protected $connection;

    /**
     * Database filling method.
     * If 'auto', Petrol will automatically insert one row every line.
     * If 'manual', you must call insertRow($data) on $this->connection to insert a row.
     * 
     * @var     string  ['auto, 'manual']
     */
    protected $fill = '';

    /**
     * The file to be parsed by parser class.
     * File must be in Petrol/src/Files.
     * 
     * @var     string
     */
    protected $file = 'test';

     /**
     * The database table to be filled.
     * 
     * @var     string
     */
    protected $table = 'simple_table';

    /**
     * All database table columns except id.
     * Must exactly match database tables.
     * Database tables must include an auto incrementing id column.
     * 
     * @var     array
     */
    protected $columns = [
        'name',
        'email',
        'address'
    ];

    /**
     * Variables to be called before loop begins.
     * These variables will be stored on the object and can be 
     * accessed with $this->key.
     * 
     * @var     array
     */
    protected $variables = [
        //
    ];

    /**
     * Construct.
     * 
     * @param   Connection  $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    /**
     * Parse the file.
     * 
     * @param   string  $line  [individual lines from file]
     * @return  array  $data  [array of columns => values for line]
     */
    protected function parse($line)
    {
        $items = explode('/', $line);

        $trimmed_items = [];

        foreach ($items as $item) {
            array_push($trimmed_items, trim($item));
        }

        $data = array_combine($this->columns, $trimmed_items);

        return $data;
    }
}
