<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * Time
 */
class TimeType extends TextType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'TIME';
    }
}
