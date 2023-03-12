<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Adapters\HandlerInterface;
use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\MySql\Facades\ColumnTypeRegistry;
use Fi1a\MySql\NamingInterface;
use Fi1a\Validation\Error;
use Fi1a\Validation\OneOf;
use Fi1a\Validation\Validator;
use PDO;

/**
 * Обработчик создания таблицы
 */
class CreateTableHandler extends AbstractMySqlHandler
{
    /**
     * @var HandlerInterface
     */
    protected $addIndexHandler;

    public function __construct(PDO $connection, NamingInterface $naming, HandlerInterface $addIndexHandler)
    {
        parent::__construct($connection, $naming);
        $this->addIndexHandler = $addIndexHandler;
    }

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
            'columns:*:primary' => OneOf::create()->generic([
                'increments' => 'boolean',
            ])->null(),
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

        $sqlPart = '';

        foreach ($query['columns'] as $index => $column) {
            $sql .= ($index > 0 ? ', ' : '') . $this->getColumnSql($column);

            $sqlPart .= $this->getAddIndexesSql($column, $query['tableName']);
        }
        $sql .= ');';

        if ($sqlPart) {
            $sql .= $sqlPart;
        }

        return $sql;
    }

    /**
     * Возвращает sql для колонок
     *
     * @param mixed[] $column
     */
    protected function getColumnSql(array $column): string
    {
        $params = isset($column['params']) ? (array) $column['params'] : null;
        $type = ColumnTypeRegistry::get(
            (string) $column['type'],
            $this->connection,
            (string) $column['columnName'],
            $params
        );
        $sql = $this->naming->wrapColumnName((string) $column['columnName']);
        if (isset($column['rename']) && $column['rename']) {
            $sql .= ' ' . $this->naming->wrapColumnName((string) $column['rename']);
        }
        $sql .= ' ' . $type->getSql();
        $sql .= ' ' . (isset($column['nullable']) && $column['nullable'] ? 'NULL' : 'NOT NULL');
        if (isset($column['default'])) {
            $sql .= ' DEFAULT ' . $type->conversionTo($column['default']);
        }
        if (isset($column['primary']) && is_array($column['primary'])) {
            $sql .= ' PRIMARY KEY';
            if (isset($column['primary']['increments']) && $column['primary']['increments']) {
                $sql .= ' AUTO_INCREMENT';
            }
        }

        return $sql;
    }

    /**
     * Возвращает sql добавления индекса
     *
     * @param mixed[] $column
     */
    protected function getAddIndexSql(string $indexType, array $column, string $tableName): string
    {
        $index = (array) $column[$indexType];
        $index['type'] = $indexType;
        $index['tableName'] = $tableName;
        $index['columns'] = [$column['columnName']];

        /** @var string $sql */
        $sql = $this->addIndexHandler->prepare([
            'type' => 'addIndex',
            'index' => $index,
        ]);

        return $sql;
    }

    /**
     * Возвращает sql добавления индексов
     *
     * @param mixed[] $column
     */
    protected function getAddIndexesSql(array $column, string $tableName): string
    {
        if (isset($column['unique']) && is_array($column['unique'])) {
            return ' ' . $this->getAddIndexSql('unique', $column, $tableName);
        }
        if (isset($column['foreign']) && is_array($column['foreign'])) {
            return ' ' . $this->getAddIndexSql('foreign', $column, $tableName);
        }
        if (isset($column['index']) && is_array($column['index'])) {
            return ' ' . $this->getAddIndexSql('index', $column, $tableName);
        }

        return '';
    }
}
