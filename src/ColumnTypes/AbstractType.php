<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

use PDO;

/**
 * Абстрактный тип колонки
 */
abstract class AbstractType implements ColumnTypeInterface
{
    /**
     * @var array<string, mixed>|null
     */
    protected $params;

    /**
     * @var PDO
     */
    protected $connection;

    protected $columnName;

    /**
     * @param array<string, mixed>|null $params
     */
    public function __construct(PDO $connection, string $columnName, ?array $params = null)
    {
        $this->connection = $connection;
        $this->columnName = $columnName;
        $this->params = $params;
    }
}
