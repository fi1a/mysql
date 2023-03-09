<?php

declare(strict_types=1);

namespace Fi1a\MySql\Facades;

use Fi1a\Facade\AbstractFacade;
use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;
use Fi1a\MySql\ColumnTypes\RegistryInterface;

/**
 * Реестр типа колонок
 *
 * @method static ColumnTypeInterface get(string $type, \PDO $connection, string $columnName, ?array $params = null)
 * @method static void add(string $type, string $className)
 * @method static bool has(string $type)
 * @method static bool remove(string $type)
 */
class ColumnTypeRegistry extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function factory(): object
    {
        return di()->get(RegistryInterface::class);
    }
}
