<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * Float
 */
class FloatType extends IntegerType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->unsigned('FLOAT');
    }

    /**
     * @inheritDoc
     */
    public function conversionFrom(string $value)
    {
        return (float) $value;
    }
}
