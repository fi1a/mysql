<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\MySql\Facades\Registry;
use Fi1a\Validation\Error;
use Fi1a\Validation\Validator;

/**
 * Обработчик создания таблицы
 */
class CreateTableHandler extends AbstractMySqlHandler
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
                'tableName' => 'required',
                'ifNotExists' => 'boolean',
                'columns' => 'array|required',
                'columns:*:columnName' => 'required',
                'columns:*:type' => 'required',
                'columns:*:nullable' => 'boolean',
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
     */
    public function prepare(array $query)
    {
        $sql = 'CREATE TABLE ';

        if ($query['ifNotExists']) {
            $sql .= 'IF NOT EXISTS ';
        }

        $sql .= $query['tableName'] . ' (';

        foreach ($query['columns'] as $index => $column) {
            $params = isset($column['params']) ? (array) $column['params'] : null;
            $type = Registry::get((string) $column['type'], $this->connection, (string) $column['columnName'], $params);
            $sql .= ($index > 0 ? ', ' : '') . '`' . $column['columnName'] . '` ' . $type->getSql();
            $sql .= ' ' . ($column['nullable'] ? 'NULL' : 'NOT NULL');
            if (isset($column['default'])) {
                $sql .= ' DEFAULT ' . $type->conversionTo($column['default']);
            }
        }

        return $sql . ');';
    }
}
