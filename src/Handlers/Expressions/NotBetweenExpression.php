<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

/**
 * Условие "NOT BETWEEN"
 */
class NotBetweenExpression extends BetweenExpression
{
    /**
     * @inheritDoc
     */
    protected function getExpressionSignSql(): string
    {
        return 'NOT BETWEEN';
    }
}
