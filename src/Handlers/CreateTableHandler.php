<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Adapters\HandlerInterface;
use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\Validation\Error;
use Fi1a\Validation\Validator;

/**
 * Обработчик создания таблицы
 */
class CreateTableHandler implements HandlerInterface
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
            $sql .= ($index > 0 ? ', ' : '') . $column['columnName'] . ' ' . $column['type'];
        }

        $sql .= ');';

        return $sql;
    }
}
