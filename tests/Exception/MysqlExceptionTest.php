<?php

namespace Petrol\Tests\Core\Database\Databases;

use Petrol\Core\Config\Config;
use Petrol\Core\Database\Databases\MysqlDatabase;
use Petrol\Tests\TestCase;

class MysqlExceptionTest extends TestCase
{
    private $config;

    public function setUp()
    {
        $this->config = new Config();
    }

    /**
     * Column number doesn't match.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Please ensure database exists and $columns field is properly set.
     */
    public function wrong_number_of_columns_throws_exception()
    {
        $mysql = new MysqlDatabase($this->config, false);

        $handle = $mysql->getHandle();

        $statement = $mysql->getStatement($handle, ['one', 'two'], 'test');

        $mysql->executeStatement($statement, ['hi']);
    }

    /**
     * Wrong column names.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Please ensure database exists and $columns field is properly set.
     */
    public function wrong_column_names_throws_exception()
    {
        $mysql = new MysqlDatabase($this->config, false);

        $handle = $mysql->getHandle();

        $statement = $mysql->getStatement($handle, ['one', 'two'], 'test');

        $mysql->executeStatement($statement, ['hi', 'hello']);
    }

    /**
     * No column names.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Please ensure database exists and $columns field is properly set.
     */
    public function no_column_names_throws_exception()
    {
        $mysql = new MysqlDatabase($this->config, false);

        $handle = $mysql->getHandle();

        $statement = $mysql->getStatement($handle, [], 'test');

        $mysql->executeStatement($statement, ['hi', 'hello']);
    }

    /**
     * Wrong config info.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Could not connect to database. Please check credentials in .env file.
     */
    public function wrong_config_info_throws_exception()
    {
        $mysql = new MysqlDatabase(new Config('tests/files/wrong_info_config.php'), false);

        $handle = $mysql->getHandle();

        $statement = $mysql->getStatement($handle, ['one, two'], 'test');

        $mysql->executeStatement($statement, ['hi', 'hello']);
    }
}
