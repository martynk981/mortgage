<?php

namespace App\Test\Repository;

use App\Repository\NibudDataProvider;

/**
 * Tests if data provider returns array
 *
 * @TODO Create more reliable tests to check returned data structure
 *
 * @author Nickolay Martynenko <martynk981@gmail.com>
 */
class NibudDataProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testDataStructureSuccess()
    {
        $provider = new NibudDataProvider();
        $this->assertInternalType('array', $provider->getMortgageQuoteTable());
        
    }
}
 