<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

use Fi1a\DB\Queries\Expressions\SqlExpression;

/**
 * Text
 */
class TextType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'TEXT';
    }

    /**
     * @inheritDoc
     */
    public function conversionTo($value): string
    {
        $string = (string) $value;
        if (!($value instanceof SqlExpression)) {
            $string = $this->connection->quote($string);
        }

        return $string;
    }

    /**
     * @inheritDoc
     */
    public function conversionFrom(string $value)
    {
        return $value;
    }
}
