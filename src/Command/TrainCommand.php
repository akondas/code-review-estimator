<?php
declare(strict_types=1);

namespace Estimator\Command;

use Phpml\CrossValidation\RandomSplit;
use Phpml\Dataset\CsvDataset;
use Phpml\Math\Statistic\Correlation;
use Phpml\ModelManager;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TrainCommand extends Command
{
    protected function configure()
    {
        $this->setName('train')
            ->setDescription('Train ML model')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataset = new RandomSplit(new CsvDataset(__DIR__.'/../../data/code-reviews.csv', 6, true, ';'), 0.1);
        $estimator = new SVR(Kernel::LINEAR);
        $estimator->train($dataset->getTrainSamples(), $dataset->getTrainLabels());

        $output->writeln(sprintf('R2: %s', pow(Correlation::pearson(
            $dataset->getTestLabels(),
            $estimator->predict($dataset->getTestSamples())
        ), 2)));

        $modelManager = new ModelManager();
        $modelManager->saveToFile($estimator, __DIR__.'/../../data/model.dat');

        $output->writeln('New model trained! 🚀');
    }
}
