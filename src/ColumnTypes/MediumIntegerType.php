<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * MediumInteger
 */
class MediumIntegerType extends IntegerType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->unsigned('MEDIUMINT');
    }
}
