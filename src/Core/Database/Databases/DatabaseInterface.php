<?php

namespace Petrol\Core\Database\Databases;

use Petrol\Core\Config\Config;

interface DatabaseInterface
{
    /**
     * Construct.
     *
     * @param Config $config
     * @param bool   $errors
     */
    public function __construct(Config $config, $errors);

    /**
     * Execute prepared statement.
     *
     * @param PDOStatement $statement
     * @param array        $data
     */
    public function executeStatement($statement, $data);

    /**
     * Get database handle.
     *
     * @return PDO handle
     */
    public function getHandle();

    /**
     * Get database information array.
     *
     * @return array
     */
    public function getInformationArray();

    /**
     * Get database prepared statement.
     *
     * @param PDO handle $handle
     * @param array      $columns
     * @param string     $table
     *
     * @return PDOStatement
     */
    public function getStatement($handle, array $columns, $table);
}
