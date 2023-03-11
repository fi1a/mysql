<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\Validation\Error;
use Fi1a\Validation\Validator;

/**
 * Обработчик добавления индекса
 */
class AddIndexHandler extends AbstractMySqlHandler
{
    /**
     * @inheritDoc
     */
    public function validate(array $query): void
    {
        $validator = new Validator();

        if (!isset($query['index']) || !is_array($query['index'])) {
            throw new QueryErrorException('Структура индекса не передана');
        }

        if (
            !isset($query['index']['type'])
            || !is_string($query['index']['type'])
            || !in_array(mb_strtolower($query['index']['type']), ['index', 'foreign', 'unique', 'primary'])
        ) {
            throw new QueryErrorException('Неизвестный тип индекса');
        }

        $rules = [];
        switch (mb_strtolower($query['index']['type'])) {
            case 'index':
            case 'unique':
                $rules = [
                    'index:name' => 'string|required',
                    'index:tableName' => 'string|required',
                    'index:columns' => 'array|required',
                    'index:columns:*' => 'string',
                ];

                break;
            case 'primary':
                $rules = [
                    'index:tableName' => 'string|required',
                    'index:columns' => 'array|required',
                    'index:columns:*' => 'string',
                    'index:increments' => 'boolean',
                ];

                break;
            case 'foreign':
                $rules = [
                    'index:name' => 'string|required',
                    'index:tableName' => 'string|required',
                    'index:columns' => 'array|required',
                    'index:columns:*' => 'string',
                    'index:on' => 'string|required',
                    'index:references' => 'array|required',
                    'index:references:*' => 'string',
                    'index:onDelete' => 'string',
                    'index:onUpdate' => 'string',
                ];

                break;
        }

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
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedOperand
     * @psalm-suppress MixedArgument
     */
    public function prepare(array $query)
    {
        $sql = 'ALTER TABLE ';
        $sql .= $this->naming->wrapTableName($query['index']['tableName']);
        $sql .= ' ADD';

        if (mb_strtolower($query['index']['type']) === 'primary') {
            $sql .= ' PRIMARY KEY';
        } elseif (mb_strtolower($query['index']['type']) === 'foreign') {
            $sql .= ' CONSTRAINT';
        } elseif (mb_strtolower($query['index']['type']) === 'unique') {
            $sql .= ' UNIQUE';
        } elseif (mb_strtolower($query['index']['type']) === 'index') {
            $sql .= ' INDEX';
        }
        if (mb_strtolower($query['index']['type']) !== 'primary') {
            $sql .= ' ' . $this->naming->wrapColumnName($query['index']['name']);
        }
        if (mb_strtolower($query['index']['type']) === 'foreign') {
            $sql .= ' FOREIGN KEY';
        }

        $columns = '';
        /** @var string $columnName */
        foreach ($query['index']['columns'] as $columnName) {
            $columns .= ($columns ? ', ' : '') . $this->naming->wrapColumnName($columnName);
        }
        $sql .= ' (' . $columns . ')';
        if (mb_strtolower($query['index']['type']) === 'foreign') {
            $sql .= ' REFERENCES ' . $this->naming->wrapTableName((string) $query['index']['on']);
            $columns = '';
            /** @var string $columnName */
            foreach ($query['index']['references'] as $columnName) {
                $columns .= ($columns ? ', ' : '') . $this->naming->wrapColumnName($columnName);
            }
            $sql .= ' (' . $columns . ')';

            if (isset($query['index']['onDelete'])) {
                $sql .= ' ON DELETE ' . $query['index']['onDelete'];
            }
            if (isset($query['index']['onUpdate'])) {
                $sql .= ' ON UPDATE ' . $query['index']['onUpdate'];
            }
        }

        return $sql . ';';
    }
}
