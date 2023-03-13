<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * Timestamp
 */
class TimestampType extends TextType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'TIMESTAMP';
    }
}
