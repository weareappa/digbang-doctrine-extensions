<?php

namespace Digbang\DoctrineExtensions;

use Digbang\DoctrineExtensions\Functions;
use Digbang\DoctrineExtensions\Types;
use Doctrine\ORM\Configuration;
use DoctrineExtensions\Types as BeberleiTypes;
use DoctrineExtensions\Query as BeberleiFunctions;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\Fluent\Builders\Builder;
use LaravelDoctrine\ORM\Configuration\CustomTypeManager;
use LaravelDoctrine\ORM\DoctrineManager;
use Scienta\DoctrineJsonFunctions\Query\AST\Functions as ScientaFunctions;

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
        $typeManager = new CustomTypeManager();

        $typeManager->addCustomTypes([
            Types\ChronosDateType::CHRONOS_DATE => Types\ChronosDateType::class,
            Types\ChronosDateTimeType::CHRONOS_DATETIME => Types\ChronosDateTimeType::class,
            Types\ChronosDateTimeTzType::CHRONOS_DATETIMETZ => Types\ChronosDateTimeTzType::class,
            Types\UuidType::UUID => Types\UuidType::class
        ]);

        $typeManager->addCustomTypes([
            BeberleiTypes\CarbonDateType::CARBONDATE => BeberleiTypes\CarbonDateType::class,
            BeberleiTypes\CarbonDateTimeType::CARBONDATETIME => BeberleiTypes\CarbonDateTimeType::class,
            BeberleiTypes\CarbonDateTimeTzType::CARBONDATETIMETZ => BeberleiTypes\CarbonDateTimeTzType::class,
            BeberleiTypes\CarbonTimeType::CARBONTIME => BeberleiTypes\CarbonTimeType::class,
            BeberleiTypes\PolygonType::FIELD => BeberleiTypes\PolygonType::class,
        ]);
    }

    private function registerMacros()
    {
        $macros = [
            Types\ChronosDateType::CHRONOS_DATE => 'chronosDate',
            Types\ChronosDateTimeType::CHRONOS_DATETIME => 'chronosDateTime',
            Types\ChronosDateTimeTzType::CHRONOS_DATETIMETZ => 'chronosDateTimeTz',
            Types\UuidType::UUID => 'uuid',

            BeberleiTypes\CarbonDateType::CARBONDATE => 'carbonDate',
            BeberleiTypes\CarbonDateTimeType::CARBONDATETIME => 'carbonDateTime',
            BeberleiTypes\CarbonDateTimeTzType::CARBONDATETIMETZ => 'carbonDateTimeTz',
            BeberleiTypes\CarbonTimeType::CARBONTIME => 'carbonTime',
            BeberleiTypes\PolygonType::FIELD => 'polygon',
        ];

        foreach ($macros as $type => $alias) {
            Builder::macro($alias, function (Builder $builder, $fieldName, $callback = null) use ($type) {
                return $builder->field($type, $fieldName, $callback);
            });
        }
    }

    private function registerFunctions()
    {
        $this->app->make(DoctrineManager::class)->extendAll(function (Configuration $configuration) {
            $configuration->setCustomDatetimeFunctions([
                'STR_TO_DATE' => BeberleiFunctions\Postgresql\StrToDate::class,
            ]);

            $configuration->setCustomNumericFunctions([
                Functions\Postgresql\NumericCastFunction::IDENTIFIER => Functions\Postgresql\NumericCastFunction::class,
                Functions\Postgresql\EarthDistanceFunction::IDENTIFIER => Functions\Postgresql\EarthDistanceFunction::class,
                Functions\Postgresql\DecimalCastFunction::IDENTIFIER => Functions\Postgresql\DecimalCastFunction::class,

                'COUNT_FILTER' => BeberleiFunctions\Postgresql\CountFilterFunction::class,
                'GREATEST' => BeberleiFunctions\Postgresql\Greatest::class,
                'LEAST' => BeberleiFunctions\Postgresql\Least::class,
            ]);

            $configuration->setCustomStringFunctions([
                Functions\Postgresql\ArrayAggFunction::IDENTIFIER => Functions\Postgresql\ArrayAggFunction::class,
                Functions\Postgresql\DateTruncFunction::IDENTIFIER => Functions\Postgresql\DateTruncFunction::class,
                Functions\Postgresql\DistinctOnFunction::IDENTIFIER => Functions\Postgresql\DistinctOnFunction::class,
                Functions\Postgresql\FilterWhereFunction::IDENTIFIER => Functions\Postgresql\FilterWhereFunction::class,
                Functions\Postgresql\InJsonArrayFunction::IDENTIFIER => Functions\Postgresql\InJsonArrayFunction::class,
                Functions\Postgresql\UnaccentFunction::IDENTIFIER => Functions\Postgresql\UnaccentFunction::class,
                Functions\Postgresql\LPadFunction::IDENTIFIER => Functions\Postgresql\LPadFunction::class,

                'AT_TIME_ZONE' => BeberleiFunctions\Postgresql\AtTimeZoneFunction::class,
                'DATE_FORMAT' => BeberleiFunctions\Postgresql\DateFormat::class,
                'DATE_PART' => BeberleiFunctions\Postgresql\DatePart::class,
                'EXTRACT' => BeberleiFunctions\Postgresql\ExtractFunction::class,
                'REGEXP_REPLACE' => BeberleiFunctions\Postgresql\RegexpReplace::class,
                'STRING_AGG' => BeberleiFunctions\Postgresql\StringAgg::class,

                ScientaFunctions\Postgresql\JsonbContains::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonbContains::class,
                ScientaFunctions\Postgresql\JsonbExists::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonbExists::class,
                ScientaFunctions\Postgresql\JsonbExistsAll::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonbExistsAll::class,
                ScientaFunctions\Postgresql\JsonbExistsAny::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonbExistsAny::class,
                ScientaFunctions\Postgresql\JsonbIsContained::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonbIsContained::class,
                ScientaFunctions\Postgresql\JsonExtractPath::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonExtractPath::class,
                ScientaFunctions\Postgresql\JsonGet::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonGet::class,
                ScientaFunctions\Postgresql\JsonGetPath::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonGetPath::class,
                ScientaFunctions\Postgresql\JsonGetPathText::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonGetPathText::class,
                ScientaFunctions\Postgresql\JsonGetText::FUNCTION_NAME => ScientaFunctions\Postgresql\JsonGetText::class,
            ]);
        });
    }
}
