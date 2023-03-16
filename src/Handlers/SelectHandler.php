<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\MySql\Facades\ColumnTypeRegistry;
use Fi1a\MySql\Facades\ExpressionRegistry;
use Fi1a\Validation\Error;
use Fi1a\Validation\OneOf;
use Fi1a\Validation\Validator;

/**
 * Обработчик запроса выборки
 */
class SelectHandler extends AbstractMySqlHandler
{
    /**
     * @inheritDoc
     * @psalm-suppress MixedArgumentTypeCoercion
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress MixedMethodCall
     */
    public function validate(array $query): void
    {
        $validator = new Validator();

        $validation = $validator->make(
            $query,
            [
                'from' => 'array|required',
                'from:table' => 'string|required',
                'from:alias' => OneOf::create()->string()->null(),
                'columns' => 'array',
                'columns:*:column' => 'array|required',
                'columns:*:column:columnName' => 'string|required',
                'columns:*:column:type' => 'string|required',
                'columns:*:column:params' => OneOf::create()->array()->null(),
                'columns:*:alias' => OneOf::create()->string()->null(),
                'where' => 'array',
            ],
        );

        $result = $validation->validate();

        if (!$result->isSuccess()) {
            /** @var Error $error */
            $error = $result->getErrors()->first();

            throw new QueryErrorException($error->getMessage() ?: 'Неизвестная ошибка');
        }
    }

    /**
     * @inheritDoc
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedAssignment
     */
    public function prepare(array $query)
    {
        $sql = 'SELECT * FROM ';
        $sql .= $this->naming->wrapTableName($query['from']['table']);
        if (is_string($query['from']['alias']) && $query['from']['alias']) {
            $sql .= ' AS ' . $query['from']['alias'];
        }
        $defaultColumnType = [
            'type' => 'text',
            'params' => null,
        ];
        foreach ($query['columns'] as $column) {
            if ($column['column']['columnName'] === '*') {
                $defaultColumnType = $column['column'];

                break;
            }
        }
        if (count($query['where'])) {
            $sql .= ' WHERE ' . $this->getWhereSql(
                $defaultColumnType,
                $query['columns'],
                $query['where']
            );
        }

        return $sql;
    }

    /**
     * Возвращает Sql условия
     *
     * @param mixed[] $defaultColumnType
     * @param mixed[] $columns
     * @param mixed[] $chain
     */
    protected function getWhereSql(
        array $defaultColumnType,
        array $columns,
        array $chain,
        bool $isAddLogic = false
    ): string {
        $sql = '';

        /** @var array<string, mixed> $item */
        foreach ($chain as $item) {
            $logic = 'AND';
            if (isset($item['logic']) && is_string($item['logic'])) {
                $logic = mb_strtoupper($item['logic']);
            }
            $sql .= $sql || $isAddLogic ? ' ' . $logic . ' ' : '';

            if (isset($item['column']) && is_array($item['column'])) {
                $sql .= '(' . $this->getWhereSql($defaultColumnType, $columns, $item['column']) . ')';

                continue;
            }

            $columnType = null;
            /** @var array{column: array{columnName: string}} $column */
            foreach ($columns as $column) {
                if (
                    $this->naming->wrapColumnName($column['column']['columnName'])
                    === $this->naming->wrapColumnName((string) $item['column'])
                ) {
                    $columnType = $column['column'];

                    break;
                }
            }
            if (!$columnType) {
                $columnType = $defaultColumnType;
            }

            $params = null;
            if (isset($columnType['params']) && is_array($columnType['params'])) {
                $params = $columnType['params'];
            }

            $type = ColumnTypeRegistry::get(
                (string) $columnType['type'],
                $this->connection,
                (string) $item['column'],
                $params
            );

            $expression = ExpressionRegistry::get(
                $item['operation'] ? (string) $item['operation'] : '=',
                $this->naming->wrapColumnName((string) $item['column']),
                $item['value'] ?? null,
                $type
            );

            $sql .= $expression->getSql();

            /** @psalm-suppress MixedArgument */
            if (isset($item['where']) && is_array($item['where']) && count($item['where'])) {
                $sql .= $this->getWhereSql($defaultColumnType, $columns, $item['where'], true);
            }
        }

        return $sql;
    }
}
