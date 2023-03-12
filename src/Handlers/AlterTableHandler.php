<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\Validation\Error;
use Fi1a\Validation\OneOf;
use Fi1a\Validation\Validator;

/**
 * Обработчик изменения таблицы
 */
class AlterTableHandler extends CreateTableHandler
{
    /**
     * @inheritDoc
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function validate(array $query): void
    {
        $validator = new Validator();

        $rules = [
            'tableName' => 'string|required',
            'dropColumns' => 'array',
            'dropColumns:*' => 'string',
            'addColumns' => 'array',
            'addColumns:*:columnName' => 'string|required',
            'addColumns:*:type' => 'string|required',
            'addColumns:*:nullable' => 'boolean',
            'addColumns:*:rename' => 'null',
            'addColumns:*:primary' => OneOf::create()->generic([
                'increments' => 'boolean',
            ])->null(),
            'addColumns:*:unique' => OneOf::create()->generic([
                'name' => 'string|required',
            ])->null(),
            'addColumns:*:foreign' => OneOf::create()->generic([
                'name' => 'string|required',
                'on' => 'string|required',
                'references' => 'string|required',
                'onDelete' => 'string',
                'onUpdate' => 'string',
            ])->null(),
            'addColumns:*:index' => OneOf::create()->generic([
                'name' => 'string|required',
            ])->null(),
            'changeColumns' => 'array',
            'changeColumns:*:columnName' => 'string|required',
            'changeColumns:*:rename' => OneOf::create()->null()->string(),
            'changeColumns:*:type' => 'string|required',
            'changeColumns:*:nullable' => 'boolean',
            'changeColumns:*:primary' => OneOf::create()->generic([
                'increments' => 'boolean',
            ])->null(),
            'changeColumns:*:unique' => OneOf::create()->generic([
                'name' => 'string|required',
            ])->null(),
            'changeColumns:*:foreign' => OneOf::create()->generic([
                'name' => 'string|required',
                'on' => 'string|required',
                'references' => 'string|required',
                'onDelete' => 'string',
                'onUpdate' => 'string',
            ])->null(),
            'changeColumns:*:index' => OneOf::create()->generic([
                'name' => 'string|required',
            ])->null(),
        ];

        /** @psalm-suppress MixedArgumentTypeCoercion */
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
     */
    public function prepare(array $query)
    {
        $sql = '';

        /** @var string $columnName */
        foreach ($query['dropColumns'] as $columnName) {
            $sql .= ($sql ? ' ' : '') . 'ALTER TABLE ' . $this->naming->wrapTableName((string) $query['tableName'])
                . ' DROP COLUMN ' . $this->naming->wrapColumnName($columnName) . ';';
        }
        /** @var mixed[] $column */
        foreach ($query['addColumns'] as $column) {
            $sql .= ($sql ? ' ' : '') . 'ALTER TABLE ' . $this->naming->wrapTableName((string) $query['tableName'])
                . ' ADD COLUMN ' . $this->getColumnSql($column) . ';';

            $sql .= $this->getAddIndexesSql($column, (string) $query['tableName']);
        }
        /** @var mixed[] $column */
        foreach ($query['changeColumns'] as $column) {
            $sql .= ($sql ? ' ' : '') . 'ALTER TABLE ' . $this->naming->wrapTableName((string) $query['tableName']);

            if (isset($column['rename']) && $column['rename']) {
                $sql .= 'CHANGE COLUMN ';
            } else {
                $sql .= 'MODIFY ';
            }
            $sql .= $this->getColumnSql($column) . ';';
            $sql .= $this->getAddIndexesSql($column, (string) $query['tableName']);
        }

        return $sql;
    }
}
