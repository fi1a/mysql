<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * Double
 */
class DoubleType extends DecimalType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->unsigned($this->precision('DOUBLE'));
    }
}
