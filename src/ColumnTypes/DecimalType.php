<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

/**
 * Decimal
 */
class DecimalType extends FloatType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->unsigned($this->precision('DECIMAL'));
    }

    /**
     * Настройки типа данных
     */
    protected function precision(string $sql): string
    {
        if (
            (isset($this->params['total']) && is_numeric($this->params['total']))
            || (isset($this->params['places']) && is_numeric($this->params['places']))
        ) {
            $sql .= ' (';
            $total = isset($this->params['total']) && is_numeric($this->params['total']) ? $this->params['total'] : 8;
            $sql .= $total;
        }
        if (isset($this->params['places']) && is_numeric($this->params['places'])) {
            $sql .= ', ' . $this->params['places'];
        }
        if (
            (isset($this->params['total']) && is_numeric($this->params['total']))
            || (isset($this->params['places']) && is_numeric($this->params['places']))
        ) {
            $sql .= ')';
        }

        return $sql;
    }
}
