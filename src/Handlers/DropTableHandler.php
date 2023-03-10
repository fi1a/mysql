<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\Validation\Error;
use Fi1a\Validation\Validator;

/**
 * Обработчик удаления таблицы
 */
class DropTableHandler extends AbstractMySqlHandler
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
                'ifExists' => 'boolean',
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
        $sql = 'DROP TABLE ';

        if ($query['ifExists']) {
            $sql .= 'IF EXISTS ';
        }

        $sql .= $this->naming->wrapTableName($query['tableName']) . ';';

        return $sql;
    }
}
