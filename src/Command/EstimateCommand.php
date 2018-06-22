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

        if($output->isVerbose()) {
            $output->writeln(sprintf('Commits: %s', $pr['commits']));
            $output->writeln(sprintf('Additions: %s', $pr['additions']));
            $output->writeln(sprintf('Deletions: %s', $pr['deletions']));
            $output->writeln(sprintf('Changed files: %s', $pr['changed_files']));
            $output->writeln(sprintf('Comments: %s', $pr['comments']));
            $output->writeln(sprintf('Review comments: %s', $pr['review_comments']));
        }

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

        $output->writeln(sprintf('Price for %s is: <info>$%s</info>', $input->getArgument('path'), round($prediction[0], 2)));
    }
}
