<?php

namespace App\Mortgage\Console\Command\Test;

use Symfony\Component\Console\Tester\CommandTester;
use App\Calculator\Console\Application;
use App\Calculator\Console\Command;

/**
 * Simple example of the CLI usage of the mortgage calculator
 *
 * Class MortgageCommandTest
 * @package App\Mortgage\Console\Command\Test
 */
class MortgageCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application('1.0');
        $application->add(new Command\Mortgage());

        $command = $application->find('mortgage');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
                'year-income'   => 10000,
                'interest-rate' => 5,
            ));
        $this->assertRegExp('/.../', $commandTester->getDisplay());
    }
}