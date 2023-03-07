<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * Binary
 */
class BinaryType extends TextType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'BINARY';
    }
}
