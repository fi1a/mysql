<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

/**
 * Условие "значение не является null или boolean"
 */
class IsNotExpression extends AbstractExpression
{
    /**
     * @inheritDoc
     */
    protected function getExpressionSignSql(): string
    {
        return 'IS NOT';
    }
}
