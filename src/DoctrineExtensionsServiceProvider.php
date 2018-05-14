<?php

namespace Digbang\DoctrineExtensions;

use Digbang\DoctrineExtensions\Functions;
use Digbang\DoctrineExtensions\Types;
use Doctrine\ORM\Configuration;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\Fluent\Builders\Builder;
use LaravelDoctrine\ORM\Configuration\CustomTypeManager;
use LaravelDoctrine\ORM\DoctrineManager;

class DoctrineExtensionsServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->registerTypes();
        $this->registerMacros();
        $this->registerFunctions();
    }

    private function registerTypes()
    {
        (new CustomTypeManager())->addCustomTypes([
            Types\ChronosDateType::CHRONOS_DATE => Types\ChronosDateType::class,
            Types\ChronosDateTimeType::CHRONOS_DATETIME => Types\ChronosDateTimeType::class,
            Types\ChronosDateTimeTzType::CHRONOS_DATETIMETZ => Types\ChronosDateTimeTzType::class,
        ]);
    }

    private function registerMacros()
    {
        $macros = [
            Types\ChronosDateType::CHRONOS_DATE => 'chronosDate',
            Types\ChronosDateTimeType::CHRONOS_DATETIME => 'chronosDateTime',
            Types\ChronosDateTimeTzType::CHRONOS_DATETIMETZ => 'chronosDateTimeTz',
        ];

        foreach ($macros as $type => $alias) {
            Builder::macro($alias, function (Builder $builder, $fieldName, $callback = null) use ($type) {
                return $builder->field($type, $fieldName, $callback);
            });
        }
    }

    private function registerFunctions()
    {
        app(DoctrineManager::class)->extendAll(function (Configuration $configuration) {
            $configuration->setCustomDatetimeFunctions([
                Functions\DateTruncFunction::IDENTIFIER => Functions\DateTruncFunction::class,
                Functions\ExtractFunction::IDENTIFIER => Functions\ExtractFunction::class,
            ]);

            $configuration->setCustomNumericFunctions([
                Functions\GreatestFunction::IDENTIFIER => Functions\GreatestFunction::class,
            ]);

            $configuration->setCustomStringFunctions([
                Functions\UnaccentFunction::IDENTIFIER => Functions\UnaccentFunction::class,
                Functions\InJsonArrayFunction::IDENTIFIER => Functions\InJsonArrayFunction::class,
            ]);
        });
    }
}
