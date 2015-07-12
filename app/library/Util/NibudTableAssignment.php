<?php
namespace App\Util;

use App\Repository\NibudDataProviderInterface;

/***
 * In order to determine if you should borrow a certain amount of money to buy a house,
 * the dutch institute for household budgets "NIBUD" publishes tables.
 *
 * The maximum mortgage amount is based on the combination of yearly income and interest rate.
 *
 * We need to be able to look up:
 *  - the highest mortgage amount for a given yearly income and interest rate
 *  - the lowest yearly income for a given mortgage amount and interest rate
 *  - the highest interest rate for a given mortgage amount yearly income
 *
 * The data comes from the frontend forms, so could contain numbers as strings.
 *
 * Please implement the methods below and add a unit test for each method.
 */
class NibudTableAssignment implements MortgageCalculateInterface
{
    /**
     * @var NibudDataProviderInterface
     */
    private $dataProvider = null;

    /**
     * @param NibudDataProviderInterface $dataProvider
     */
    public function __construct(NibudDataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return NibudDataProviderInterface
     */
    public function getDataProvider()
    {
        return $this->dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function getMaximumResponsibleMortgageAmount($yearlyIncome, $interestRate)
    {
        Assert::isInteger($yearlyIncome, 0);
        Assert::isFloat($interestRate, 0);

        $yearlyIncomeIndex = $this->getIntervalIndexFromRange(
            $this->getYearlyIncomeRange(),
            $yearlyIncome
        );

        $interestRange  = $this->getInterestRateRange($yearlyIncomeIndex);
        $interestIndex  = $this->getIntervalIndexFromRange(array_keys($interestRange), $interestRate);
        $mortgageIndex  = $interestRange[$interestIndex];
        $mortgageAmount = round($yearlyIncome/($mortgageIndex/100));

        return $mortgageAmount;
    }

    /**
     * @inheritdoc
     */
    public function getMinimalYearlyIncome($mortgageAmount, $interestRate)
    {
        Assert::isInteger($mortgageAmount, 0);
        Assert::isFloat($interestRate, 0);

        $yearlyIncomeRange = $this->getYearlyIncomeRange();
        $yearlyIncomeIndex = min($yearlyIncomeRange);

        $interestRange = $this->getInterestRateRange($yearlyIncomeIndex);
        $interestIndex = $this->getIntervalIndexFromRange(array_keys($interestRange), $interestRate);
        $mortgageIndex = $interestRange[$interestIndex];
        $yearlyIncome  = round($mortgageAmount*($mortgageIndex/100));

        return $yearlyIncome;
    }

    /**
     * @inheritdoc
     */
    public function getMaximumInterestRate($mortgageAmount, $yearlyIncome)
    {
        Assert::isInteger($mortgageAmount, 0);
        Assert::isInteger($yearlyIncome, 0);

        $yearlyIncomeIndex = $this->getIntervalIndexFromRange(
            $this->getYearlyIncomeRange(),
            $yearlyIncome
        );

        $mortgageAmountIndex = $yearlyIncome*100/$mortgageAmount;
        $interestRange = $this->getInterestRateRange($yearlyIncomeIndex);
        $interestRateIndex = $this->getIntervalIndexFromRange(array_values($interestRange), $mortgageAmountIndex);

        arsort($interestRange);
        return array_search($interestRateIndex, $interestRange);
    }

    /**
     * Calculates the min value of the interval {@see $range} which matches {@see $value}
     *
     * @param array $range
     * @param float|int $value
     * @return float|int
     */
    private function getIntervalIndexFromRange(array $range, $value)
    {
        try {
            Assert::isInteger($value, 0);
        } catch (\InvalidArgumentException $e) {
            $value = (float) $value;
            Assert::isFloat($value, 0);
        }

        $minIntervalValue = $range[0];

        foreach ($range as $rangeValue) {
            if ($rangeValue == $value) {
                $minIntervalValue = $rangeValue;
                break;
            }
            if ($rangeValue < $value && $minIntervalValue < $rangeValue) {

                    $minIntervalValue = $rangeValue;
            }
        }

        return $minIntervalValue;
    }

    /**
     * Returns the yearly income range array
     *
     * @return array
     */
    private function getYearlyIncomeRange()
    {
        return array_keys($this->getDataProvider()->getMortgageQuoteTable());
    }

    /**
     * Returns appropriate interestRate=>mortgageIndex array according provided yearly income value
     *
     * @param int $yearlyIncome
     * @return array
     */
    private function getInterestRateRange($yearlyIncome)
    {
        return $this->getDataProvider()->getMortgageQuoteTable()[$yearlyIncome];
    }
}