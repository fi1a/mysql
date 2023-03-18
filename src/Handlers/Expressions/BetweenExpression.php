<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\DB\Exceptions\QueryErrorException;

/**
 * Условие "BETWEEN"
 */
class BetweenExpression extends AbstractExpression
{
    /**
     * @inheritDoc
     */
    protected function getSecondPartSql(): string
    {
        if (!is_array($this->value) || isset($this->value['columnName']) || count($this->value) !== 2) {
            throw new QueryErrorException('Ошибка в формате значения');
        }
        $values = array_values($this->value);

        return $this->type->conversionTo($values[0]) . ' AND ' . $this->type->conversionTo($values[1]);
    }

    /**
     * @inheritDoc
     */
    protected function getExpressionSignSql(): string
    {
        return 'BETWEEN';
    }
}
