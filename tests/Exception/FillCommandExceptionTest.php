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
use Symfony\Component\Filesystem\Filesystem;

class FillCommandExceptionTest extends DatabaseTestCase
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
        $this->app->add(new ConsoleFill(new Filesystem()));
        $this->command = $this->app->find('fill');
        $this->command_tester = new CommandTester($this->command);

        parent::__construct();
        parent::setUp();
    }

    /**
     * Invalid name argument given.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Invalid input
     */
    public function invalid_name_throws_exception()
    {
        $this->assertFalse(file_exists(getcwd().'/src/Fillers/FillNothing.php'));

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'name' => 'nothing',
            '--quiet' => true
        ));
    }

    /**
     * Missing columns in filler.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage $columns property not set.
     */
    public function missing_columns_property_throws_exception()
    {
        $data = new FillData('Petrol\\Tests\\files\\FillerNoColumns', false, 'config.php', '.env', 'test');

        $fill = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $fill->handle($data);
    }

    /**
     * Missing file name in filler.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage $file property not set.
     */
    public function missing_file_property_throws_exception()
    {
        $data = new FillData('Petrol\\Tests\\files\\FillerNoFile', false, 'config.php', '.env', 'test');

        $fill = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $fill->handle($data);
    }

    /**
     * Missing table in filler.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage $table property not set.
     */
    public function missing_table_property_throws_exception()
    {
        $data = new FillData('Petrol\\Tests\\files\\FillerNoTable', false, 'config.php', '.env', 'test');

        $fill = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $fill->handle($data);
    }

    /**
     * Incorrect fill property in filler.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage $fill property set to unknown value.
     */
    public function wrong_fill_property_throws_exception()
    {
        $data = new FillData('Petrol\\Tests\\files\\FillerWrongFill', false, 'config.php', '.env', 'test');

        $fill = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $fill->handle($data);
    }

    /**
     * Incorrectly set variables property in filler.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage $variables property must be array.
     */
    public function wrong_variables_property_throws_exception()
    {
        $data = new FillData('Petrol\\Tests\\files\\FillerWrongVariables', false, 'config.php', '.env', 'test');

        $fill = new FillHandler(new ArgvInput(), new ConsoleOutput());

        $fill->handle($data);
    }
}
