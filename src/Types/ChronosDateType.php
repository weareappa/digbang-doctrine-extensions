<?php

namespace Digbang\DoctrineExtensions\Types;

use Cake\Chronos\Date;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateType;

class ChronosDateType extends DateType
{
    const CHRONOS_DATE = 'chronos_date';

    public function getName()
    {
        return static::CHRONOS_DATE;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        $dateTime = parent::convertToPHPValue($value, $platform);

        return Date::instance($dateTime);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
