<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * MediumText
 */
class MediumTextType extends TextType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'MEDIUMTEXT';
    }
}
