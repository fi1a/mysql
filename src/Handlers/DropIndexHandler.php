<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\Validation\ChainInterface;
use Fi1a\Validation\Error;
use Fi1a\Validation\OneOf;
use Fi1a\Validation\Validator;

/**
 * Обработчик запроса удаления индекса
 */
class DropIndexHandler extends AbstractMySqlHandler
{
    /**
     * @inheritDoc
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress MixedMethodCall
     */
    public function validate(array $query): void
    {
        $validator = new Validator();

        /** @var string[]|ChainInterface[] $rules */
        $rules = [
            'indexType' => 'in("index", "foreign", "primary", "unique")|required',
            'indexName' => OneOf::create()->string()->null(),
            'tableName' => 'string|required',
        ];

        $validation = $validator->make($query, $rules);

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
     */
    public function prepare(array $query)
    {
        $sql = 'ALTER TABLE ' . $this->naming->wrapColumnName($query['tableName']) . ' DROP ';

        if (mb_strtolower($query['indexType']) === 'primary') {
            $sql .= 'PRIMARY KEY';
        } elseif (mb_strtolower($query['indexType']) === 'foreign') {
            $sql .= 'FOREIGN KEY';
        } elseif (mb_strtolower($query['indexType']) === 'unique' || mb_strtolower($query['indexType']) === 'index') {
            $sql .= 'INDEX';
        }
        if (mb_strtolower($query['indexType']) !== 'primary') {
            $sql .= ' ' . $this->naming->wrapColumnName($query['indexName']);
        }

        return $sql . ';';
    }
}
