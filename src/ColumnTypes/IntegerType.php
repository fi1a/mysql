<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * Integer
 */
class IntegerType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->unsigned('INT');
    }

    /**
     * @inheritDoc
     */
    public function conversionTo($value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        return $value === 0 ? '0' : (string) $value;
    }

    /**
     * @inheritDoc
     */
    public function conversionFrom(string $value)
    {
        return (int) $value;
    }

    /**
     * Добавляет UNSIGNED
     */
    protected function unsigned(string $sql): string
    {
        if (isset($this->params['unsigned']) && $this->params['unsigned'] === true) {
            $sql .= ' UNSIGNED';
        }

        return $sql;
    }
}
