<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

/**
 * Условие "не равно"
 */
class NotEqExpression extends AbstractExpression
{
    /**
     * @inheritDoc
     */
    protected function getExpressionSignSql(): string
    {
        return '<>';
    }
}
