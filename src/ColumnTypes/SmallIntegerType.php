<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * SmallInteger
 */
class SmallIntegerType extends IntegerType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->unsigned('SMALLINT');
    }
}
