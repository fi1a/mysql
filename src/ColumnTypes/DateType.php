<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * Date
 */
class DateType extends TextType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'DATE';
    }
}
