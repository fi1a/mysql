<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

/**
 * Условие "like"
 */
class LikeExpression extends AbstractExpression
{
    /**
     * @inheritDoc
     */
    protected function getExpressionSignSql(): string
    {
        return 'LIKE';
    }
}
