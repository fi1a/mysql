<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\DB\Exceptions\QueryErrorException;

/**
 * Условие "IN"
 */
class InExpression extends AbstractExpression
{
    /**
     * @inheritDoc
     */
    protected function getSecondPartSql(): string
    {
        if (!is_array($this->value) || !count($this->value) || isset($this->value['columnName'])) {
            throw new QueryErrorException('Должен быть передан массив значений');
        }
        $inSql = '';
        /** @var mixed $value */
        foreach ($this->value as $value) {
            $inSql .= ($inSql ? ', ' : '') . $this->type->conversionTo($value);
        }

        return '(' . $inSql . ')';
    }

    /**
     * @inheritDoc
     */
    protected function getExpressionSignSql(): string
    {
        return 'IN';
    }
}
