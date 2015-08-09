<?php

namespace Petrol\Core\Database;

use Petrol\Core\Database\Databases\DatabaseInterface;

class Connection
{
    private $database;

    private $handle;

    private $statement;

    /**
     * Construct.
     *
     * @param DatabaseInterface $database
     */
    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
        $this->handle = $this->database->getHandle();
    }

    /**
     * Get database information array.
     *
     * @return array
     */
    public function getConnectionInformation()
    {
        return $this->database->getInformationArray();
    }

    /**
     * Get database handle.
     *
     * @return PDO object
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * Get database PDO statement.
     *
     * @return string
     */
    public function getStatement()
    {
        return $this->statement();
    }

    /**
     * Insert row into database by executing statement.
     *
     * @param array $data
     */
    public function insertRow(array $data)
    {
        $this->database->executeStatement($this->statement, $data);
    }

    /**
     * Set statement on Connection object.
     *
     * @param array  $columns
     * @param string $table
     */
    public function setStatement($columns, $table)
    {
        $this->statement = $this->database->getStatement($this->handle, $columns, $table);
    }
}
