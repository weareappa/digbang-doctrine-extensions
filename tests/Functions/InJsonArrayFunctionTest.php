<?php
declare(strict_types=1);

namespace Digbang\DoctrineExtensions\Functions;

use Digbang\DoctrineExtensions\stubs\Person;
use Doctrine\ORM\Configuration;

class InJsonArrayFunctionTest extends FunctionTestCase
{
    protected function setUpFunction(Configuration $configuration): void
    {
        $configuration->addCustomStringFunction(InJsonArrayFunction::IN_JSON_ARRAY, InJsonArrayFunction::class);
    }

    protected function getDQL(): string
    {
        return sprintf('SELECT p.id FROM %s p WHERE IN_JSON_ARRAY(p.lotteryNumbers, :search) = 1', Person::class);
    }

    protected function getSQL(): string
    {
        return 'SELECT p0_.id AS id_0 FROM Person p0_ WHERE p0_.lotteryNumbers ?| ARRAY[\'?\'] = 1';
    }

    public function test_dql_to_sql_comparison()
    {
        $query = $this->entityManager->createQuery($this->getDQL());

        $this->assertEquals($this->getSQL(), $query->getSQL());
    }
}
