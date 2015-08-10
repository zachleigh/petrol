<?php

namespace Petrol\Tests\Core\Commands\NewCommand;

use Petrol\Core\Commands\NewCommand\ConsoleNew;
use Petrol\Tests\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use org\bovigo\vfs\vfsStream;

class NewCommandExceptionTest extends TestCase
{
    private $app;

    private $command;

    private $command_tester;

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
        $this->root = vfsStream::url('root');
        $this->path = vfsStream::url('root/FillTest.php');
    }

    /**
     * Duplicate class name.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Filler for Test already exists.
     */
    public function duplicate_class_name_returns_error()
    {
        $this->assertFalse(file_exists($this->path));

        touch($this->path);

        $this->assertTrue(file_exists($this->path));

        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'name' => 'Test',
            '--path' => $this->root,
        ));
    }

    /**
     * Invalid path option.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Can not write to Filler path.
     */
    public function invalid_path_returns_error()
    {
        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'name' => 'Test',
            '--path' => '/',
        ));
    }
}
