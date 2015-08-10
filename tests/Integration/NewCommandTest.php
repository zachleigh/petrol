<?php

use Petrol\Core\Commands\NewCommand\ConsoleNew;
use Petrol\Tests\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use org\bovigo\vfs\vfsStream;

class NewCommandTest extends TestCase
{
    private $app;

    private $command;

    private $command_tester;

    private $root;

    private $path;

    /**
     *
     */
    public function setUp()
    {
        $this->app = new Application();
        $this->app->add(new ConsoleNew(new Filesystem()));
        $this->command = $this->app->find('new');
        $this->command_tester = new CommandTester($this->command);

        vfsStream::setup();
        $this->root = vfsStream::url('root/');
        $this->path = vfsStream::url('root/FillTest.php');
    }

    /**
     * New command makes new class.
     *
     * @test
     */
    public function new_command_makes_new_class()
    {
        $this->assertFalse(file_exists($this->path));

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'name' => 'Test',
            '--path' => $this->root,
        ));

        $this->assertTrue(file_exists($this->path));

        $result = 'Filler successfully created.'."\n";

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    /**
     * New command makes camel case class.
     *
     * @test
     */
    public function new_command_makes_new_camelcase_class()
    {
        $this->assertFalse(file_exists(vfsStream::url('root/testFile.php')));

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'name' => 'test_file',
            '--path' => $this->root,
        ));

        $this->assertTrue(file_exists(vfsStream::url('root/FillTestFile.php')));

        $result = 'Filler successfully created.'."\n";

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }

    /**
     * New command makes spaceless class name.
     *
     * @test
     */
    public function new_command_makes_new_spaceless_class()
    {
        $this->assertFalse(file_exists(vfsStream::url('root/testFile.php')));

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'name' => 'test file',
            '--path' => $this->root,
        ));

        $this->assertTrue(file_exists(vfsStream::url('root/FillTestFile.php')));

        $result = 'Filler successfully created.'."\n";

        $this->assertEquals($result, $this->command_tester->getDisplay());
    }
}
