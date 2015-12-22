<?php

namespace Petrol\tests;

class DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    private static $pdo = null;

    protected $connection = null;

    /**
     * Construct.
     */
    public function __construct()
    {
        if (file_exists('.env')) {
            // \Dotenv::load(getcwd());
            $dotenv = new \Dotenv\Dotenv(getcwd());
            $dotenv->load();
        }

        $this->dsn = 'mysql:dbname='.getenv('DB_DATABASE').';host='.getenv('DB_HOST');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
        $this->db_name = getenv('DB_DATABASE');

        $connection = $this->getConnection();
        $this->setUpTable($connection);
    }

    /**
     * Get database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    final public function getConnection()
    {
        if ($this->connection === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO($this->dsn, $this->username, $this->password);
            }

            $this->connection = $this->createDefaultDBConnection(self::$pdo, $this->db_name);
        }

        return $this->connection;
    }

    /**
     * Get XML dataset.
     *
     * @param string $file
     *
     * @return string
     */
    public function getDataSet($file = 'empty.xml')
    {
        $file = 'tests/datasets/'.$file;

        return $this->createXmlDataSet($file);
    }

    /**
     * Set up the test database table.
     *
     * @param PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection $connection
     */
    protected function setUpTable($connection)
    {
        $pdo = $connection->getConnection();

        $sql = 'CREATE TABLE IF NOT EXISTS simple_table (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL,
            address VARCHAR(100)
        )';

        $pdo->exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS books_array (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            book_id VARCHAR(50) NOT NULL,
            info TEXT NOT NULL
        )';

        $pdo->exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS books_columns (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            author VARCHAR(100) NOT NULL,
            title VARCHAR(100) NOT NULL,
            genre VARCHAR(100) NOT NULL,
            price VARCHAR(50) NOT NULL,
            publish_date VARCHAR(50) NOT NULL,
            description TEXT NOT NULL
        )';

        $pdo->exec($sql);
    }
}
