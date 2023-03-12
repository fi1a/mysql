<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\Validation\Error;
use Fi1a\Validation\Validator;

/**
 * Обработчик переименования таблицы
 */
class RenameTableHandler extends AbstractMySqlHandler
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
                'newTableName' => 'string|required',
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
     */
    public function prepare(array $query)
    {
        return 'RENAME TABLE ' . $this->naming->wrapTableName((string) $query['tableName']) . ' TO '
            . $this->naming->wrapTableName((string) $query['newTableName']) . ';';
    }
}
