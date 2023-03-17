<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers\Expressions;

use Fi1a\DB\Exceptions\UnknownExpressionException;
use Fi1a\MySql\ColumnTypes\ColumnTypeInterface;
use Fi1a\MySql\NamingInterface;
use InvalidArgumentException;

/**
 * Реестр выражений
 */
class Registry implements RegistryInterface
{
    /**
     * @var array<string, class-string<ExpressionInterface>>
     */
    protected $registry = [];

    /**
     * @inheritDoc
     */
    public function get(
        string $operation,
        $columnName,
        $value,
        ColumnTypeInterface $type,
        NamingInterface $naming
    ): ExpressionInterface {
        if (!$this->has($operation)) {
            throw new UnknownExpressionException(sprintf('Неизвестное выражение %s', $operation));
        }
        $class = $this->registry[mb_strtolower($operation)];

        return new $class($columnName, $value, $type, $naming);
    }

    /**
     * @inheritDoc
     */
    public function add(string $operation, string $className): void
    {
        if (!is_subclass_of($className, ExpressionInterface::class)) {
            throw new InvalidArgumentException(
                sprintf('Класс должен реализовывать интерфейс %s', ExpressionInterface::class)
            );
        }

        $this->registry[mb_strtolower($operation)] = $className;
    }

    /**
     * @inheritDoc
     */
    public function has(string $operation): bool
    {
        return array_key_exists(mb_strtolower($operation), $this->registry);
    }

    /**
     * @inheritDoc
     */
    public function remove(string $operation): bool
    {
        if (!$this->has($operation)) {
            return false;
        }

        unset($this->registry[mb_strtolower($operation)]);

        return true;
    }
}
