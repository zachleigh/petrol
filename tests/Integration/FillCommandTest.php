<?php

namespace Petrol\Tests\Core\Commands\FillCommand;

use Petrol\Core\Commands\FillCommand\ConsoleFill;
use Petrol\Core\Commands\FillCommand\FillData;
use Petrol\Core\Commands\FillCommand\FillHandler;
use Petrol\Tests\DatabaseTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Tester\CommandTester;

class FillCommandTest extends DatabaseTestCase
{
    protected $app;

    protected $command;

    protected $command_tester;

    /**
     *
     */
    public function setUp()
    {
        $this->app = new Application();
        $this->app->add(new ConsoleFill());
        $this->command = $this->app->find('fill');
        $this->command_tester = new CommandTester($this->command);

        parent::__construct();
        parent::setUp();
    }

    /**
     * Put data files in files directory.
     */
    public static function setUpBeforeClass()
    {
        copy(getcwd().'/tests/files/simple.txt', getcwd().'/src/Files/simple.txt');

        copy(getcwd().'/tests/files/books.xml', getcwd().'/src/Files/books.xml');
    }

    /**
     * Tear down files in files directory.
     */
    public static function tearDownAfterClass()
    {
        unlink(getcwd().'/src/Files/simple.txt');

        unlink(getcwd().'/src/Files/books.xml');
    }

    /**
     * ConsoleFill class calls handle method of Fill class. Should hit invalid input
     * exception on FillHandler class.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Invalid input
     */
    public function console_fill_calls_handle_method_on_fill_handler()
    {
        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'name' => 'Test',
            '--quiet' => true
        ));
    }

    /**
     * Basic filler with simple_table.
     *
     * @test
     */
    public function parse_and_fill_simple_table()
    {
        $data = new FillData('Petrol\\Tests\\files\\SimpleTable', false, 'config.php', '.env', 'test');

        $handler = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $handler->handle($data);

        $actual = $this->getConnection()->createQueryTable('simple_table', 'SELECT * FROM simple_table');

        $expected = $this->getDataSet('simple_table.xml')->getTable('simple_table');

        $this->assertTablesEqual($expected, $actual);
    }

    /**
     * Basic filler with fill property set to manual.
     *
     * @test
     */
    public function parse_and_fill_simple_table_manual()
    {
        $data = new FillData('Petrol\\Tests\\files\\ManualSimpleTable', false, 'config.php', '.env', 'test');

        $handler = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $handler->handle($data);

        $actual = $this->getConnection()->createQueryTable('simple_table', 'SELECT * FROM simple_table');

        $expected = $this->getDataSet('simple_table.xml')->getTable('simple_table');

        $this->assertTablesEqual($expected, $actual);
    }

    /**
     * Basic filler with fill property set to dump.
     *
     * @test
     */
    public function parse_and_dump_simple_table()
    {
        $data = new FillData('Petrol\\Tests\\files\\DumpSimpleTable', false, 'config.php', '.env', 'test');

        $handler = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $handler->handle($data);

        $actual = $this->getConnection()->createQueryTable('simple_table', 'SELECT * FROM simple_table');

        $expected = $this->getDataSet('empty.xml')->getTable('simple_table');

        $this->assertTablesEqual($expected, $actual);

        $this->expectOutputString('Array'."\n".
            '('."\n".
            '    [name] => Bob Smith'."\n".
            '    [email] => bob@example.com'."\n".
            '    [address] => 3974 South Belvie St.'."\n".
            ')'."\n".
            'Array'."\n".
            '('."\n".
            '    [name] => Jean Samson'."\n".
            '    [email] => jean@example.com'."\n".
            '    [address] => 456 North Main'."\n".
            ')'."\n".
            'Array'."\n".
            '('."\n".
            '    [name] => George Higgins'."\n".
            '    [email] => george@example.com'."\n".
            '    [address] => 9844 East South Ave.'."\n".
            ')'."\n".
            'Array'."\n".
            '('."\n".
            '    [name] => Mike Victors'."\n".
            '    [email] => mike@example.com'."\n".
            '    [address] => 987 Cheese Street'."\n".
            ')'."\n".
            'Array'."\n".
            '('."\n".
            '    [name] => Betty Lou Victors'."\n".
            '    [email] => betty@example.com'."\n".
            '    [address] => 987 North Colorado Bvd.'."\n".
            ')'."\n"
        );
    }

    /**
     * Basic filler with parser cleanExplode method.
     *
     * @test
     */
    public function parse_and_fill_simple_table_with_parser_trait()
    {
        $data = new FillData('Petrol\\Tests\\files\\SimpleTableWithParser', false, 'config.php', '.env', 'test');

        $handler = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $handler->handle($data);

        $actual = $this->getConnection()->createQueryTable('simple_table', 'SELECT * FROM simple_table');

        $expected = $this->getDataSet('simple_table.xml')->getTable('simple_table');

        $this->assertTablesEqual($expected, $actual);
    }

    /**
     * Parse XML and store as JSON array.
     *
     * @test
     */
    public function parse_xml_and_fill_json_array_table()
    {
        $data = new FillData('Petrol\\Tests\\files\\BooksJsonArray', false, 'config.php', '.env', 'test');

        $handler = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $handler->handle($data);

        $actual = $this->getConnection()->createQueryTable('books_array', 'SELECT * FROM books_array');

        $expected = $this->getDataSet('books.xml')->getTable('books_array');

        $this->assertTablesEqual($expected, $actual);
    }

    /**
     * Parse XML and store as individual columns.
     *
     * @test
     */
    public function parse_xml_and_fill_columns_table()
    {
        $data = new FillData('Petrol\\Tests\\files\\BooksColumns', false, 'config.php', '.env', 'test');

        $handler = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $handler->handle($data);

        $actual = $this->getConnection()->createQueryTable('books_columns', 'SELECT * FROM books_columns');

        $expected = $this->getDataSet('books.xml')->getTable('books_columns');

        $this->assertTablesEqual($expected, $actual);
    }
}
