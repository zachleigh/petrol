<?php

namespace Petrol\Tests\files;

use Petrol\Core\Contracts\Filler;
use Petrol\Core\Database\Connection;
use Petrol\Core\Helpers\Traits\Parser;
use Petrol\Core\Helpers\Traits\User;
use Petrol\Core\Helpers\Traits\XmlParser;

class BooksColumns extends Filler
{
    use Parser, User, XmlParser;

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
    protected $fill = 'manual';

    /**
     * The file to be parsed by the Filler. File must be in Petrol/src/Files/.
     *
     * @var string
     */
    protected $file = 'books.xml';

    /**
     * The database table to be filled. Table must be created before filling.
     *
     * @var string
     */
    protected $table = 'books_columns';

    /**
     * Database table columns excluding id.
     *
     * @var array
     */
    protected $columns = [
        'author',
        'title',
        'genre',
        'price',
        'publish_date',
        'description'
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
     * PDO statements to be created on object. Statements will be stored on the
     * object and can be accessed with $this->key.
     * 
     * @var array
     */
    protected $statements = [
        // 'key' => 'SELECT * FROM table WHERE BINARY column = :column'
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
        $this->setRootTag('catalog');

        $data = $this->xmlToData($line, 'book', $this->columns);

        if (!is_null($data)) {
            $this->connection->insertRow($data);
        }
    }
}
