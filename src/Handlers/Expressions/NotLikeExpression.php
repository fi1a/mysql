<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

/**
 * Условие "not like"
 */
class NotLikeExpression extends AbstractExpression
{
    /**
     * @inheritDoc
     */
    protected function getExpressionSignSql(): string
    {
        return 'NOT LIKE';
    }
}
