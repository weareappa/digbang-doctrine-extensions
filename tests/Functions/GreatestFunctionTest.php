<?php
declare(strict_types=1);

namespace Digbang\DoctrineExtensions\Functions;

use Digbang\DoctrineExtensions\stubs\Person;
use Doctrine\ORM\Configuration;

class GreatestFunctionTest extends FunctionTestCase
{
    protected function setUpFunction(Configuration $configuration): void
    {
        $configuration->addCustomStringFunction(GreatestFunction::IDENTIFIER, GreatestFunction::class);
    }

    protected function getDQL(): string
    {
        return sprintf('SELECT GREATEST(p.age, p.fingers) FROM %s p', Person::class);
    }

    protected function getSQL(): string
    {
        return 'SELECT greatest(p0_.age,p0_.fingers) AS sclr_0 FROM Person p0_';
    }
}
