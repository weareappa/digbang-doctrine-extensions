<?php

namespace Digbang\DoctrineExtensions\Types;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeTzType;

class ChronosDateTimeTzType extends DateTimeTzType
{
    const CHRONOS_DATETIMETZ = 'chronos_datetimetz';

    public function getName()
    {
        return static::CHRONOS_DATETIMETZ;
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
