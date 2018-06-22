<?php
declare(strict_types=1);

namespace Estimator\Command;

use Github\Client;
use Phpml\ModelManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class EstimateCommand extends Command
{
    protected function configure()
    {
        $this->setName('estimate')
            ->setDescription('Estimate cost of code review')
            ->addArgument('path', InputArgument::REQUIRED, 'github pull request path, example: symfony/symfony/pull/27686')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        [$author, $repo, , $number] = explode('/', $input->getArgument('path'));
        $client = new Client();

        $output->writeln(sprintf('Fetching %s pull request data', $input->getArgument('path')));
        $pr = $client->api('pull_request')->show($author, $repo, $number);

        $modelManager = new ModelManager();
        $estimator = $modelManager->restoreFromFile(__DIR__.'/../../data/model.dat');
        $prediction = $estimator->predict([[
            $pr['commits'],
            $pr['additions'],
            $pr['deletions'],
            $pr['changed_files'],
            $pr['comments'],
            $pr['review_comments']
        ]]);

        $output->writeln(sprintf('Price for %s is: $%s', $input->getArgument('path'), round($prediction[0], 2)));
    }
}