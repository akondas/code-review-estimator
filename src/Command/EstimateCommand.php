<?php
declare(strict_types=1);

namespace Estimator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class EstimateCommand extends Command
{
    protected function configure()
    {
        $this->setName('estimate')
            ->setDescription('Estimate cost of code review')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}