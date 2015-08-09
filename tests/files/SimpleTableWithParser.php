<?php

namespace Petrol\Tests\files;

use Petrol\Core\Database\Connection;
use Petrol\Core\Helpers\Traits\Parser;
use Petrol\Fillers\Filler;

class SimpleTableWithParser extends Filler
{
    use Parser;

    /**
     * Database connection class.
     *
     * @var Petrol\Core\Database\Connection
     */
    protected $connection;

    /**
     * Database filling method. If 'auto', Petrol will automatically insert one row
     * every line. If 'manual', insertRow($data) must be called on $this->connection
     * to insert a row. 'dump' will dump results to console instead of filling db.
     *
     * @var string ['auto, 'manual', 'dump']
     */
    protected $fill = 'auto';

    /**
     * The file to be parsed by the Filler. File must be in Petrol/src/Files/.
     *
     * @var string
     */
    protected $file = 'simple.txt';

    /**
     * The database table to be filled. Table must be created before filling.
     *
     * @var string
     */
    protected $table = 'simple_table';

    /**
     * Database table columns excluding id.
     *
     * @var array
     */
    protected $columns = [
        'name',
        'email',
        'address'
    ];

    /**
     * Variables to be declared before loop begins. These variables will be stored
     * on the object and can be accessed with $this->key.
     *
     * @var array
     */
    protected $variables = [
        //
    ];

    /**
     * Construct.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    /**
     * Parse the file. Petrol will go through $file line by line and send each line
     * to this parse method.
     *
     * @param string $line [individual lines from file]
     *
     * @return array $data  [array of columns => values for line]
     */
    protected function parse($line)
    {
        return array_combine($this->columns, $this->cleanExplode('/', $line));
    }
}
