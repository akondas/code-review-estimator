<?php
declare(strict_types=1);

namespace Estimator\Command;

use Github\Client;
use Phpml\ModelManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class EstimateWizardCommand extends Command
{
    protected function configure()
    {
        $this->setName('estimate:wizard')
            ->setDescription('Estimate cost of code review based on provided numbers')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $commits = $helper->ask($input, $output, new Question('Commits: ', 0));
        $additions = $helper->ask($input, $output, new Question('Additions: ', 0));
        $deletions = $helper->ask($input, $output, new Question('Deletions: ', 0));
        $changedFiles = $helper->ask($input, $output, new Question('Changed files: ', 0));
        $comments = $helper->ask($input, $output, new Question('Comments: ', 0));
        $reviewComments = $helper->ask($input, $output, new Question('Review comments: ', 0));

        $modelManager = new ModelManager();
        $estimator = $modelManager->restoreFromFile(__DIR__.'/../../data/model.dat');
        $prediction = $estimator->predict([[
            $commits,
            $additions,
            $deletions,
            $changedFiles,
            $comments,
            $reviewComments
        ]]);

        $output->writeln(sprintf('Price for PR is: <info>$%s</info>', round($prediction[0], 2)));
    }
}
