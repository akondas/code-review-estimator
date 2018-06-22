<?php
declare(strict_types=1);

namespace Estimator\Command;

use Github\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class FetchPullRequestsCommand extends Command
{
    protected function configure()
    {
        $this->setName('fetch:pull-requests')
            ->setDescription('Fetch pull requests data for given repo')
            ->addArgument('repo', InputArgument::REQUIRED, 'github repository, example: author/repo')
            ->addOption('state', 's', InputOption::VALUE_OPTIONAL, 'Pull request state', 'open')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        [$author, $repo] = explode('/', $input->getArgument('repo'));
        $client = new Client();

        $output->writeln(sprintf('Pull requests [%s] statistics for %s', $input->getOption('state'), $input->getArgument('repo')));
        $output->writeln('commits;additions;deletions;changed_files;comments;review_comments');

        foreach($client->api('pull_request')->all($author, $repo, ['state' => $input->getOption('state')]) as $pr) {
            $data = $client->api('pull_request')->show($author, $repo, $pr['number']);
            $output->writeln(sprintf(
                '%s;%s;%s;%s;%s;%s',
                $data['commits'],
                $data['additions'],
                $data['deletions'],
                $data['changed_files'],
                $data['comments'],
                $data['review_comments']
            ));
        }

        $output->writeln('');
    }
}
