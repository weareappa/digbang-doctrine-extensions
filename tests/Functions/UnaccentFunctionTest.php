<?php
declare(strict_types=1);

namespace Digbang\DoctrineExtensions\Functions;

use Digbang\DoctrineExtensions\stubs\Person;
use Doctrine\ORM\Configuration;

class UnaccentFunctionTest extends FunctionTestCase
{
    protected function setUpFunction(Configuration $config): void
    {
        $config->addCustomStringFunction(UnaccentFunction::IDENTIFIER, UnaccentFunction::class);
    }
    protected function getDQL(): string
    {
        return sprintf('SELECT UNACCENT(p.name) FROM %s p', Person::class);
    }

    protected function getSQL(): string
    {
        return 'SELECT unaccent(p0_.name) AS sclr_0 FROM Person p0_';
    }
}
