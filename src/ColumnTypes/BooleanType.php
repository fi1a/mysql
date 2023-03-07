<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * Boolean
 */
class BooleanType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'BOOLEAN';
    }

    /**
     * @inheritDoc
     */
    public function conversionTo($value): string
    {
        return $value === true || $value === 1 || $value === '1' ? '1' : '0';
    }

    /**
     * @inheritDoc
     */
    public function conversionFrom(string $value)
    {
        return $value === '1';
    }
}
