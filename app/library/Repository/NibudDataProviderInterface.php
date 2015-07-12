<?php
namespace App\Repository;

/**
 * NibudDataProviderInterface
 *
 * Provides data for the mortgage calculator
 *
 * @author Nickolay Martynenko <martynk981@gmail.com>
 */
interface NibudDataProviderInterface
{
    /**
     * Return the mortgageQuote table.
     * [yearlyIncome => [interestRate => mortgageAmount as percentage of yearly income, ], ]
     *
     * @return array
     */
    public function getMortgageQuoteTable();
}