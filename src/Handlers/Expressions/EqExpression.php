<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

/**
 * Условие "равно"
 */
class EqExpression extends AbstractExpression
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->column . '=' . $this->type->conversionTo($this->value);
    }
}
