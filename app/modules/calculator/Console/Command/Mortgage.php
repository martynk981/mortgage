<?php

namespace App\Calculator\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

use App\Util;
use App\Repository\NibudDataProvider;

class Mortgage extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('mortgage')
            ->setDescription('Check your maximum mortgage value')
            ->addArgument(
                'year-income',
                InputArgument::REQUIRED,
                'Your yearly income'
            )
            ->addArgument(
                'interest-rate',
                InputArgument::REQUIRED,
                'Appropriate interest rate'
            )
            ->setHelp('Provide your yearly income and interest rate to get mortgage value');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yearIncome   = $input->getArgument('year-income');
        $interestRate = $input->getArgument('interest-rate');

        try {
            $yearIncome   = Util\Assert::isInteger(
                filter_var($yearIncome, FILTER_VALIDATE_INT),
                0
            );

            $interestRate = Util\Assert::isFloat(
                filter_var($interestRate, FILTER_VALIDATE_FLOAT)
            );

            $nibudTableAssignment = new Util\NibudTableAssignment(
                new NibudDataProvider()
            );

            $amount = $nibudTableAssignment->getMaximumResponsibleMortgageAmount($yearIncome, $interestRate);

            $output->writeln(
                sprintf('<info>Your maximum mortgage amount is: %d</info>', $amount)
            );

        } catch (\InvalidArgumentException $e) {
            $output->writeln(
                sprintf('<error>%s</error>', $e->getMessage())
            );
        }
    }
}