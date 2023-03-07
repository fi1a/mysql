<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * BigInteger
 */
class BigIntegerType extends IntegerType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->unsigned('BIGINT');
    }
}
