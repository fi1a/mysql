<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Queries\Expressions\SqlExpression;

/**
 * Char
 */
class CharType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'CHAR(' . $this->getLength() . ')';
    }

    /**
     * @inheritDoc
     */
    public function conversionTo($value): string
    {
        $string = (string) $value;
        if (!($value instanceof SqlExpression)) {
            if (strlen($string) > $this->getLength()) {
                throw new QueryErrorException(
                    sprintf('Длина значения поля "%s" больше %d', $this->columnName, $this->getLength())
                );
            }
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

    /**
     * Возвращает длину
     */
    protected function getLength(): int
    {
        $length = isset($this->params['length']) ? (int) $this->params['length'] : 255;
        if ($length <= 0 || $length > 255) {
            throw new QueryErrorException(
                sprintf('Длина значения поля "%s" должна быть в диапазоне от 0 до 255', $this->columnName)
            );
        }

        return $length;
    }
}
