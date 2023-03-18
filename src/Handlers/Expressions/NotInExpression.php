<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

/**
 * Условие "NOT IN"
 */
class NotInExpression extends InExpression
{
    /**
     * @inheritDoc
     */
    protected function getExpressionSignSql(): string
    {
        return 'NOT IN';
    }
}
