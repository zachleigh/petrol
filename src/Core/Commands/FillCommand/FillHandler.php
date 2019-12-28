<?php

namespace Petrol\Core\Commands\FillCommand;

use Dotenv\Dotenv;
use Petrol\Core\Commands\MakeCommand\MakeEnv;
use Petrol\Core\Database\Build\ConnectionFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class FillHandler
{
    private $input;

    private $output;

    /**
     * Construct.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * Handle requests to fill database.
     *
     * @param FillData $data
     */
    public function handle(FillData $data)
    {
        if ($data->source === 'console' && !file_exists('.env')) {
            $this->createEnvOrAbort();
        }

        $filler = $this->buildFillerClass($data);

        $filler->validate();

        $this->output->writeln('<info>Working...</info>');

        $filler->execute();

        $this->output->writeln('<info>Successfully wrote to database</info>');
    }

    /**
     * Build Filler class instance.
     *
     * @param array $data
     *
     * @return Filler_object
     */
    private function buildFillerClass($data)
    {
        $this->validateClassName($data->file_namespace);

        $connection_factory = new ConnectionFactory($data->config_path);

        $connection = $connection_factory->build($this->output, $data->errors);

        return new $data->file_namespace($connection);
    }

    /**
     * Create a new .env file or abort command.
     */
    private function createEnvOrAbort()
    {
        $helper = new QuestionHelper();

        $this->output->writeln('<error>There is currently no .env file to read database info from.</error>');

        $question = new ConfirmationQuestion('<question>Create a .env file now? (yes/no)</question>');

        $create_env = $helper->ask($this->input, $this->output, $question);

        if (!$create_env) {
            $this->output->writeln('<comment>Aborted</comment>');

            return;
        }

        $make_env = new MakeEnv();

        $make_env->execute($this->input, $this->output);

        $dotenv = new Dotenv(getcwd());

        $dotenv->load();
    }

    /**
     * Validate the name of the filler class.
     *
     * @param string $class_name [Full path to class]
     */
    private function validateClassName($class_name)
    {
        if (!class_exists($class_name)) {
            throw new \Exception('Invalid input.'."\n".'Filler for '.$class_name.' does not exist.');
        }
    }
}
