<?php

namespace Petrol\Tests\Core\Database;

use Petrol\Core\Database\Build\ConnectionFactory;
use Petrol\Tests\TestCase;
use Symfony\Component\Console\Output\ConsoleOutput;

class DatabaseExceptionTest extends TestCase
{
    /**
     * Invalid database name in config file throws exception.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Database type not found!
     */
    public function invalid_database_name_throws_exception()
    {
        $connection_factory = new ConnectionFactory('tests/files/wrong_config.php');

        $connection_factory->build(new ConsoleOutput(), false);
    }
}
