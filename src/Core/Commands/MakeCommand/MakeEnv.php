<?php

namespace Petrol\Core\Commands\MakeCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class MakeEnv
{
    private $helper;

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->helper = new QuestionHelper();
    }

    /**
     * Execute command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (file_exists('.env')) {
            $question = new ConfirmationQuestion('<error>A .env file already exists in this package.</error>'."\n\n".'<question>Overwrite the existing .env file? (y/n)</question>');

            $overwrite = $this->helper->ask($input, $output, $question);

            if (!$overwrite) {
                $output->writeln('<comment>.env file creation aborted</comment>');

                return;
            }
        }

        $fields = $this->getEnvInfo($input, $output);

        $stub_path = getcwd().'/src/Core/Stubs/envstub';

        $stub_data = file_get_contents($stub_path);

        $stub_data = $this->setEnvFields($stub_data, $fields);

        file_put_contents('.env', $stub_data);

        $output->writeln('<info>.env file successfully created.</info>');
    }

    /**
     * Question user for env file info.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return array
     */
    private function getEnvInfo(InputInterface $input, OutputInterface $output)
    {
        $fields = [];

        $question = new Question('Please enter your database hostname: ', 'localhost');

        $fields['host'] = $this->helper->ask($input, $output, $question);

        $question = new Question('Please enter the database name: ');

        $fields['database'] = $this->helper->ask($input, $output, $question);

        $question = new Question('Please enter your username: ');

        $fields['username'] = $this->helper->ask($input, $output, $question);

        $question = new Question('Please enter your database password: ');

        $question->setHidden(true);

        $fields['password'] = $this->helper->ask($input, $output, $question);

        return $fields;
    }

    /**
     * Set database.
     *
     * @param string $stub_data
     * @param string $database
     *
     * @return string
     */
    private function setDatabase($stub_data, $database)
    {
        return str_replace('DummyDatabase', $database, $stub_data);
    }

    /**
     * Set fields in the stub data string.
     *
     * @param string $stub_data
     * @param array  $fields
     *
     * @return string
     */
    private function setEnvFields($stub_data, $fields)
    {
        $stub_data = $this->setHost($stub_data, $fields['host']);

        $stub_data = $this->setDatabase($stub_data, $fields['database']);

        $stub_data = $this->setUsername($stub_data, $fields['username']);

        $stub_data = $this->setPassword($stub_data, $fields['password']);

        return $stub_data;
    }

    /**
     * Set host.
     *
     * @param string $stub_data
     * @param string $host
     *
     * @return string
     */
    private function setHost($stub_data, $host)
    {
        return str_replace('DummyHost', $host, $stub_data);
    }

    /**
     * Set password.
     *
     * @param string $stub_data
     * @param string $password
     *
     * @return string
     */
    private function setPassword($stub_data, $password)
    {
        return str_replace('DummyPassword', $password, $stub_data);
    }

    /**
     * Set username.
     *
     * @param string $stub_data
     * @param string $username
     *
     * @return string
     */
    private function setUsername($stub_data, $username)
    {
        return str_replace('DummyUsername', $username, $stub_data);
    }
}
