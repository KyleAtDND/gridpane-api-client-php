<?php

namespace Gridpane\Console;

use Gridpane\Api\Exceptions\ApiResponseException;
use Gridpane\Api\HttpClient;
use Psy\Configuration;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('console')
            ->setDescription('Test out features of the php api client.')
            ->addArgument('token', InputArgument::OPTIONAL);
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     *
     * @throws RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Configuration;

        $client = new HttpClient();
        $client->withToken($input->getArgument('token'));

        try {
            $data = $client->users()->me();
            $config->setStartupMessage(
                '<fg=green>Hi '.
                $data->user->name.
                '. An instance of HttpClient using your credentials is stored on $client variable.</>'
            );
        } catch (ApiResponseException $e) {
            $config->setStartupMessage('<fg=red>Invalid client credentials</>');
        }

        $shell = new Shell($config);
        $shell->setScopeVariables(compact('client'));
        $shell->run();
    }
}
