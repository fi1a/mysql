<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * DateTime
 */
class DateTimeType extends TextType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'DATETIME';
    }
}
