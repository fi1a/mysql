<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * TinyInteger
 */
class TinyIntegerType extends IntegerType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->unsigned('TINYINT');
    }
}
