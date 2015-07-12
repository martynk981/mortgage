<?php
namespace App\Test\Util;

use App\Util\NibudTableAssignment;
use App\Util\MortgageCalculateInterface;

/**
 * Test mortgage calculator.
 *
 * @author Nickolay Martynenko <martynk981@gmail.com>
 */
class NibudTableAssignmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The data for the mock data provider
     *
     * @var array
     */
    private $mockProviderDataTable = [
        0      => ['0.0' => 9.5, '2.501' => 10.0, '3.001' => 10.0, '3.501' => 10.5],
        19500  => ['0.0' => 9.5, '2.501' => 10.0, '3.001' => 10.0, '3.501' => 10.5,],
        20000  => ['0.0' => 10.5, '2.501' => 11.0, '3.001' => 11.5, '3.501' => 12.0,],
    ];

    /**
     * Data for testing the maximum mortgage amount:
     * [yearlyIncome, interestRate, mortgageAmount (valid result)]
     *
     * @return array
     */
    public function mortgageAmountProvider()
    {
        return [

            [950, 1.0, 10000],
            [1000, 2.501, 10000],
            [1000, 2.801, 10000],
            [1050, 3.501, 10000],
            [1050, 3.502, 10000],

            [10001, 1.0, 105274],
            [10001, 2.501, 100010],
            [10001, 2.801, 100010],
            [10001, 3.501, 95248],
            [10001, 3.502, 95248],

            [20001, 1.0, 190486],
            [20001, 2.501, 181827],
            [20001, 2.801, 181827],
            [20001, 3.002, 173922],
            [20001, 3.501, 166675],
            [20001, 3.502, 166675],
        ];
    }

    /**
     * Data for testing the appropriate yearly income:
     * [mortgageAmount, interestRate, yearlyIncome (valid result)]
     *
     * @return array
     */
    public function yearlyIncomeProvider()
    {
        return [
            [10000, 1.0, 950],
            [10000, 2.501, 1000],
            [10000, 2.801, 1000],
            [10000, 3.501, 1050],
            [10000, 3.502, 1050],
        ];
    }

    /**
     * Data for testing the maximum interest rate:
     * [mortgageAmount, yearlyIncome, interestRate (valid result)]
     *
     * @return array
     */
    public function interestRateProvider()
    {
        return [
            [105274, 10000, 0.0],
            [181827, 20001, 2.501],
            [100010, 10001, 3.001],
            [95248, 10001, 3.001],
            [166675, 20001, 3.501],
        ];
    }

    /**
     * Data to check fail calls
     *
     * @return array
     */
    public function incorrectDataProvider()
    {
        return [
            [-1, 'test'],
            [null, -1],
            ['test', null],
            [-1, null],
            ['test', 'test'],
        ];
    }

    /**
     * @var MortgageCalculateInterface
     */
    private $calculator = null;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->calculator = new NibudTableAssignment(
            $this->getDataProviderMock()
        );
    }

    public function testNibudTableAssignmentInstanceSuccess()
    {
        $this->assertTrue($this->calculator instanceof MortgageCalculateInterface);
    }

    /**
     * @dataProvider mortgageAmountProvider
     *
     * @depends testNibudTableAssignmentInstanceSuccess
     */
    public function testMaximumResponsibleMortgageAmountSuccess($yearlyIncome, $interestRate, $mortgageAmount)
    {
        $this->assertEquals(
            $mortgageAmount,
            $this->calculator->getMaximumResponsibleMortgageAmount($yearlyIncome, $interestRate)
        );
    }

    /**
     * @dataProvider yearlyIncomeProvider
     *
     * @depends testNibudTableAssignmentInstanceSuccess
     */
    public function testMinimalYearlyIncomeSuccess($mortgageAmount, $interestRate, $yearlyIncome)
    {
        $this->assertEquals(
            $yearlyIncome,
            $this->calculator->getMinimalYearlyIncome($mortgageAmount, $interestRate)
        );
    }

    /**
     * @dataProvider interestRateProvider
     *
     * @depends testNibudTableAssignmentInstanceSuccess
     */
    public function testMaximumInterestRateSuccess($mortgageAmount, $yearlyIncome, $interestRate)
    {
        $this->assertEquals(
            $interestRate,
            $this->calculator->getMaximumInterestRate($mortgageAmount, $yearlyIncome)
        );
    }

    /**
     * @dataProvider incorrectDataProvider
     *
     * @depends testNibudTableAssignmentInstanceSuccess
     */
    public function testMaximumResponsibleMortgageAmountFail($yearlyIncome, $interestRate)
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->calculator->getMaximumResponsibleMortgageAmount($yearlyIncome, $interestRate);
    }

    /**
     * @dataProvider incorrectDataProvider
     *
     * @depends testNibudTableAssignmentInstanceSuccess
     */
    public function testMinimalYearlyIncomeFail($mortgageAmount, $interestRate)
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->calculator->getMaximumResponsibleMortgageAmount($mortgageAmount, $interestRate);
    }

    /**
     * @dataProvider incorrectDataProvider
     *
     * @depends testNibudTableAssignmentInstanceSuccess
     */
    public function testMaximumInterestRateFail($mortgageAmount, $yearlyIncome)
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->calculator->getMaximumResponsibleMortgageAmount($mortgageAmount, $yearlyIncome);
    }

    private function getDataProviderMock()
    {
        $mock = $this->getMock('App\Repository\NibudDataProviderInterface');
        $mock->expects($this->any())
            ->method('getMortgageQuoteTable')
            ->will($this->returnValue($this->mockProviderDataTable));

        return $mock;
    }
}