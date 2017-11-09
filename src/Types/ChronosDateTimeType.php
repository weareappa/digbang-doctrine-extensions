<?php

namespace Digbang\DoctrineExtensions\Types;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;

class ChronosDateTimeType extends DateTimeType
{
    const CHRONOS_DATETIME = 'chronos_datetime';

    public function getName()
    {
        return static::CHRONOS_DATETIME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        $dateTime = parent::convertToPHPValue($value, $platform);

        return Chronos::instance($dateTime);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
