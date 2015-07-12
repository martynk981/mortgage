<?php

namespace App\Util;

/**
 * MortgageCalculateInterface
 *
 * Provides methods for the mortgage calculator
 *
 * @author Nickolay Martynenko <martynk981@gmail.com>
 */
interface MortgageCalculateInterface
{
    /**
     * Find the Maximum responsible MortgageAmount for given income and interest rate.
     *
     * @param integer $yearlyIncome
     * @param float   $interestRate
     *
     * @return int
     */
    public function getMaximumResponsibleMortgageAmount($yearlyIncome, $interestRate);

    /**
     * Get the yearly income required to get a mortgage of the given amount, based on the interest rate.
     *
     * @param $mortgageAmount
     * @param $interestRate
     *
     * @return integer
     */
    public function getMinimalYearlyIncome($mortgageAmount, $interestRate);

    /**
     * Find the highest interest rate that would allow you to borrow the mortgageAmount with yearlyIncome, or zero if none.
     *
     * @param $mortgageAmount
     * @param $yearlyIncome
     *
     * @return float
     */
    public function getMaximumInterestRate($mortgageAmount, $yearlyIncome);
}