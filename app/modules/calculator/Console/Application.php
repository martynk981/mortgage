<?php
namespace App\Calculator\Console;

use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    /**
     * @inheritdoc
     */
    public function __construct($version)
    {
        parent::__construct('Mortgage tool application', $version);
        $this->add(new Command\Mortgage());
    }
}