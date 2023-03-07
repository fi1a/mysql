<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * LongText
 */
class LongTextType extends TextType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'LONGTEXT';
    }
}
