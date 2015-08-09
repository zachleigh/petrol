<?php

use Petrol\Core\Database\Build\ConnectionFactory;
use Petrol\tests\TestCase;
use Symfony\Component\Console\Output\ConsoleOutput;

class ConnectionFactoryTest extends TestCase
{   
    protected $connection_factory;

    /**
     * 
     */
    protected function setUp()
    {
        $this->connection_factory = new ConnectionFactory();
    }

    /**
     * Connection factory builds instance of Connection.
     *
     * @test
     */
    public function connection_factory_returns_connection_instance()
    {
        $result = $this->connection_factory->build(new ConsoleOutput(), false);

        $this->assertInstanceOf('Petrol\Core\Database\Connection', $result);
    }
}
