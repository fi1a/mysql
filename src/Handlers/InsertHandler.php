<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\MySql\Facades\ColumnTypeRegistry;
use Fi1a\Validation\Error;
use Fi1a\Validation\Validator;

/**
 * Обработчик удаления таблицы
 */
class InsertHandler extends AbstractMySqlHandler
{
    /**
     * @inheritDoc
     */
    public function validate(array $query): void
    {
        $validator = new Validator();

        $validation = $validator->make(
            $query,
            [
                'tableName' => 'string|required',
                'columns' => 'array|required',
                'columns:*:columnName' => 'string|required',
                'columns:*:type' => 'string|required',
                'columns:*:nullable' => 'boolean',
                'columns:*:rename' => 'null',
                'rows' => 'array|required',
            ]
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
     * @psalm-suppress MixedOperand
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArgument
     */
    public function prepare(array $query)
    {
        $sql = 'INSERT INTO ' . $this->naming->wrapTableName($query['tableName']) . ' (';

        $sqlColumns = '';
        $types = [];
        foreach ($query['columns'] as $column) {
            $sqlColumns .= ($sqlColumns ? ', ' : '') . $this->naming->wrapColumnName($column['columnName']);
            $params = isset($column['params']) ? (array) $column['params'] : null;
            $types[(string) $column['columnName']] = ColumnTypeRegistry::get(
                (string) $column['type'],
                $this->connection,
                (string) $column['columnName'],
                $params
            );
        }

        $sql .= $sqlColumns . ') VALUES ';

        $rowsSql = '';
        foreach ($query['rows'] as $row) {
            $rowSql = '';
            foreach ($query['columns'] as $column) {
                $value = $row[(string) $column['columnName']] ?? null;
                $type = $types[(string) $column['columnName']];
                $rowSql .= ($rowSql !== '' ? ', ' : '') . (is_null($value) ? 'NULL' : $type->conversionTo($value));
            }
            $rowsSql .= ($rowsSql ? ', ' : '') . '(' . $rowSql . ')';
        }

        return $sql . $rowsSql . ';';
    }
}
