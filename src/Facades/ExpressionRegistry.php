<?php

declare(strict_types=1);

namespace Fi1a\MySql\Facades;

use Fi1a\Facade\AbstractFacade;
use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;
use Fi1a\MySql\Handlers\Expressions\ExpressionInterface as EI;
use Fi1a\MySql\Handlers\Expressions\RegistryInterface;
use Fi1a\MySql\NamingInterface as NI;

/**
 * Реестр условий
 *
 * @method static EI get(string $operation, $columnName, $value, ColumnTypeInterface $type, NI $naming)
 * @method static void add(string $operation, string $className)
 * @method static bool has(string $operation)
 * @method static bool remove(string $operation)
 */
class ExpressionRegistry extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function factory(): object
    {
        return di()->get(RegistryInterface::class);
    }
}
