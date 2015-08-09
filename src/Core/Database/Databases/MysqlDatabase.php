<?php

namespace Petrol\Core\Database\Databases;

use Petrol\Core\Config\Config;

class MysqlDatabase implements DatabaseInterface
{
    private $config;

    private $errors;

    /**
     * Construct.
     *
     * @param Config $config
     * @param string $errors
     */
    public function __construct(Config $config, $errors)
    {
        $this->config = $config;
        $this->errors = $errors;
    }

    /**
     * Execute mysql statement.
     *
     * @param PDOStatement $statement
     * @param array        $data
     */
    public function executeStatement($statement, $data)
    {
        try {
            $statement->execute($data);
        } catch (\PDOException $pdo_errors) {
            if ($this->errors) {
                echo $pdo_errors;
            }

            throw new \Exception('Database error.'."\n".'Please ensure database exists and $columns field is properly set.');
        }
    }

    /**
     * Get mysql database handle.
     *
     * @return PDO handle
     */
    public function getHandle()
    {
        $mysql = $this->getInformationArray();

        $driver = $mysql['driver'];

        $charset = $mysql['charset'];

        $host = $mysql['host'];

        $dbname = $mysql['database_name'];

        $username = $mysql['username'];

        $password = $mysql['password'];

        try {
            $handle = new \PDO("$driver:host=$host;dbname=$dbname;charset=$charset", $username, $password);

            $handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $pdo_errors) {
            if ($this->errors) {
                echo $pdo_errors;
            }

            throw new \Exception('Database error.'."\n".'Could not connect to database. Please check credentials in .env file.');
        }

        return $handle;
    }

    /**
     * Get mysql database information from config file.
     *
     * @return array
     */
    public function getInformationArray()
    {
        $config = new Config();

        $array = $config->get('connections')['mysql'];

        return $array;
    }

    /**
     * Get mysql prepared statement.
     *
     * @param PDO handle $handle
     * @param array      $columns
     * @param string     $table
     *
     * @return PDOStatement
     */
    public function getStatement($handle, array $columns, $table)
    {
        $names = implode(',', $columns);

        $placeholders = ':'.implode(', :', $columns);

        $mysql = "INSERT INTO $table ".'('.$names.') value ('.$placeholders.')';

        try {
            $statement = $handle->prepare($mysql);
        } catch (\PDOException $pdo_errors) {
            if ($this->errors) {
                echo $pdo_errors;
            }

            throw new \Exception('Database error. Problem creating database statement.');
        }

        return $statement;
    }
}
