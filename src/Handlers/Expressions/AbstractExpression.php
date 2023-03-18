<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;
use Fi1a\MySql\NamingInterface;

/**
 * Условие
 */
abstract class AbstractExpression implements ExpressionInterface
{
    /**
     * @var mixed
     */
    protected $column;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var ColumnTypeInterface
     */
    protected $type;

    /**
     * @var NamingInterface
     */
    protected $naming;

    /**
     * Возвращает часть sql для значка выражения
     */
    abstract protected function getExpressionSignSql(): string;

    /**
     * @param mixed $column
     * @param mixed $value
     */
    public function __construct($column, $value, ColumnTypeInterface $type, NamingInterface $naming)
    {
        $this->column = $column;
        $this->value = $value;
        $this->type = $type;
        $this->naming = $naming;
    }

    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        $sql = $this->getFirstPartSql();
        $sql .= ' ' . $this->getExpressionSignSql() . ' ';
        $sql .= $this->getSecondPartSql();

        return $sql;
    }

    /**
     * Sql второй части
     */
    protected function getSecondPartSql(): string
    {
        if (
            is_array($this->value)
            && isset($this->value['columnName'])
            && is_string($this->value['columnName'])
            && $this->value['columnName'] !== ''
        ) {
            return $this->naming->wrapColumnName($this->value['columnName']);
        }

        return $this->type->conversionTo($this->value);
    }

    /**
     * Sql первой части
     */
    protected function getFirstPartSql(): string
    {
        if (
            is_array($this->column)
            && isset($this->column['columnName'])
            && is_string($this->column['columnName'])
            && $this->column['columnName'] !== ''
        ) {
            return $this->naming->wrapColumnName($this->column['columnName']);
        }

        return $this->type->conversionTo($this->column);
    }
}
