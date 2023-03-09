<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\MySql\Facades\ColumnTypeRegistry;
use Fi1a\Validation\Error;
use Fi1a\Validation\OneOf;
use Fi1a\Validation\Validator;

/**
 * Обработчик создания таблицы
 */
class CreateTableHandler extends AbstractMySqlHandler
{
    /**
     * @inheritDoc
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress MixedMethodCall
     */
    public function validate(array $query): void
    {
        $validator = new Validator();

        $rules = [
            'tableName' => 'string|required',
            'ifNotExists' => 'boolean',
            'columns' => 'array|required',
            'columns:*:columnName' => 'string|required',
            'columns:*:type' => 'string|required',
            'columns:*:nullable' => 'boolean',
            'columns:*:primary' => OneOf::create()->array()->null(),
            'columns:*:unique' => OneOf::create()->generic([
                'name' => 'string|required',
            ])->null(),
            'columns:*:foreign' => OneOf::create()->generic([
                'name' => 'string|required',
                'on' => 'string|required',
                'references' => 'string|required',
                'onDelete' => 'string',
                'onUpdate' => 'string',
            ])->null(),
            'columns:*:index' => OneOf::create()->generic([
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
     * @psalm-suppress MixedOperand
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArgument
     */
    public function prepare(array $query)
    {
        $sql = 'CREATE TABLE ';

        if (isset($query['ifNotExists']) && $query['ifNotExists']) {
            $sql .= 'IF NOT EXISTS ';
        }

        $sql .= $this->naming->wrapTableName($query['tableName']) . ' (';

        $constraint = '';

        foreach ($query['columns'] as $index => $column) {
            $params = isset($column['params']) ? (array) $column['params'] : null;
            $type = ColumnTypeRegistry::get(
                (string) $column['type'],
                $this->connection,
                (string) $column['columnName'],
                $params
            );
            $sql .= ($index > 0 ? ', ' : '')
                . $this->naming->wrapColumnName($column['columnName']) . ' ' . $type->getSql();
            $sql .= ' ' . (isset($column['nullable']) && $column['nullable'] ? 'NULL' : 'NOT NULL');
            if (isset($column['default'])) {
                $sql .= ' DEFAULT ' . $type->conversionTo($column['default']);
            }
            if (isset($column['primary']) && is_array($column['primary'])) {
                $sql .= ' PRIMARY KEY';
                if (isset($column['increments']) && $column['increments']) {
                    $sql .= ' AUTO_INCREMENT';
                }
            }
            if (isset($column['unique']) && is_array($column['unique'])) {
                $constraint .= ($constraint ? ', ' : '')
                    . 'UNIQUE KEY ' . $this->naming->wrapColumnName($column['unique']['name'])
                    . ' (' . $this->naming->wrapColumnName($column['columnName']) . ')';
            }
            if (isset($column['foreign']) && is_array($column['foreign'])) {
                $constraint .= ($constraint ? ', ' : '')
                    . 'FOREIGN KEY ' . $this->naming->wrapColumnName($column['foreign']['name']) . ' ('
                    . $this->naming->wrapColumnName($column['columnName']) . ') '
                    . 'REFERENCES ' . $this->naming->wrapTableName($column['foreign']['on']) . ' '
                    . '(' . $this->naming->wrapColumnName($column['foreign']['references']) . ')';
                if (isset($column['foreign']['onDelete'])) {
                    $constraint .= ' ON DELETE ' . $column['foreign']['onDelete'];
                }
                if (isset($column['foreign']['onUpdate'])) {
                    $constraint .= ' ON UPDATE ' . $column['foreign']['onUpdate'];
                }
            }
            if (isset($column['index']) && is_array($column['index'])) {
                $constraint .= ($constraint ? ', ' : '')
                    . 'INDEX ' . $this->naming->wrapColumnName($column['index']['name']) . ' ('
                    . $this->naming->wrapColumnName($column['columnName']) . ')';
            }
        }
        if ($constraint) {
            $sql .= ', ' . $constraint;
        }

        return $sql . ');';
    }
}
