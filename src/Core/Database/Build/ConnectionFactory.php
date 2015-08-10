<?php

namespace Petrol\Core\Database\Build;

use Petrol\Core\Config\Config;
use Petrol\Core\Database\Connection;

class ConnectionFactory
{
    private $config;

    /**
     * Construct.
     *
     * @param string $config_file [path to config file]
     */
    public function __construct($config_file = 'config.php')
    {
        $this->config = new Config($config_file);
    }

    /**
     * Build connection class.
     *
     * @param bool $errors
     *
     * @return Connection
     */
    public function build($errors)
    {
        $database_class = $this->buildDatabaseClass($errors);

        $connection = new Connection($database_class);

        return $connection;
    }

    /**
     * Database class factory.
     *
     * @param bool $errors
     *
     * @return mixed, Database_object
     */
    private function buildDatabaseClass($errors)
    {
        $database_class_name = $this->getDatabaseClassName();

        $this->validateDatabaseClassName($database_class_name);

        return new $database_class_name($this->config, $errors);
    }

    /**
     * Get name of database from Config.
     *
     * @return string
     */
    private function getDatabaseClassName()
    {
        $database_type = $this->config->get('default');

        if ($database_type === '4D') {
            $database_type = 'FourD';
        }

        $database_class = 'Petrol\\Core\\Database\\Databases\\'.ucfirst($database_type).'Database';

        return $database_class;
    }

    /**
     * Validate the generated class name.
     *
     * @param string $database_class
     */
    private function validateDatabaseClassName($database_class)
    {
        if (!class_exists($database_class)) {
            throw new \Exception('Database type not found! Please check database field in config.php.');
        }
    }
}
