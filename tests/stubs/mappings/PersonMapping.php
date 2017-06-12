<?php
declare(strict_types=1);

namespace Digbang\DoctrineExtensions\stubs\mappings;

use Digbang\DoctrineExtensions\stubs\Person;
use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;

class PersonMapping extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return Person::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     */
    public function map(Fluent $builder)
    {
        $builder->integer('id')->primary();
        $builder->string('name');
        $builder->integer('age');
        $builder->integer('fingers')->default('10');
    }
}
