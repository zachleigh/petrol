<?php

namespace Petrol\Tests\Core\Commands\MakeCommand;

use Petrol\Core\Commands\MakeCommand\ConsoleMake;
use Petrol\Tests\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class MakeCommandExceptionTest extends TestCase
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
        $this->app->add(new ConsoleMake(new Filesystem()));
        $this->command = $this->app->find('make');
        $this->command_tester = new CommandTester($this->command);
    }

    /**
     *  Invalid argument given.
     *
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Argument sadfsd does not exist.
     */
    public function invalid_make_command_throws_exception()
    {
        $this->command_tester->execute(array(
            'command' => $this->command->getName(),
            'item' => 'sadfsd',
        ));
    }
}
