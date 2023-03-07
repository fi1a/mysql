<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * String
 */
class StringType extends CharType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'VARCHAR(' . $this->getLength() . ')';
    }
}
