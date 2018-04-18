<?php
declare(strict_types=1);

namespace Digbang\DoctrineExtensions\Functions;

use Digbang\DoctrineExtensions\DoctrineIntegrationTestCase;
use Doctrine\ORM\Configuration;

abstract class FunctionTestCase extends DoctrineIntegrationTestCase
{
    abstract protected function setUpFunction(Configuration $configuration): void;

    abstract protected function getDQL(): string;

    abstract protected function getSQL(): string;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpFunction($this->entityManager->getConfiguration());
    }

    public function test_dql_to_sql_comparison()
    {
        $query = $this->entityManager->createQuery($this->getDQL());

        $this->assertEquals($this->getSQL(), $query->getSQL());
    }
}
