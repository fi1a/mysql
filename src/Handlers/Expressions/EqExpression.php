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
        $sql = '';
        if (
            is_array($this->column)
            && isset($this->column['columnName'])
            && is_string($this->column['columnName'])
            && $this->column['columnName'] !== ''
        ) {
            $sql .= $this->naming->wrapColumnName($this->column['columnName']);
        } else {
            $sql .= $this->type->conversionTo($this->column);
        }

        $sql .= '=';

        if (
            is_array($this->value)
            && isset($this->value['columnName'])
            && is_string($this->value['columnName'])
            && $this->value['columnName'] !== ''
        ) {
            $sql .= $this->naming->wrapColumnName($this->value['columnName']);
        } else {
            $sql .= $this->type->conversionTo($this->value);
        }

        return $sql;
    }
}
