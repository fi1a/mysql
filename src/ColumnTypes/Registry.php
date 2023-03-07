<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

use Fi1a\DB\Exceptions\UnknownTypeException;
use InvalidArgumentException;
use PDO;

/**
 * Реестр типа колонок
 */
class Registry implements RegistryInterface
{
    /**
     * @var array<string, class-string<ColumnTypeInterface>>
     */
    protected $registry = [];

    /**
     * @inheritDoc
     */
    public function get(string $type, PDO $connection, string $columnName, ?array $params = null): ColumnTypeInterface
    {
        if (!$this->has($type)) {
            throw new UnknownTypeException(sprintf('Неизвестный тип %s', $type));
        }
        $class = $this->registry[mb_strtolower($type)];

        return new $class($connection, $columnName, $params);
    }

    /**
     * @inheritDoc
     */
    public function add(string $type, string $className): void
    {
        if (!is_subclass_of($className, ColumnTypeInterface::class)) {
            throw new InvalidArgumentException(
                sprintf('Класс должен реализовывать интерфейс %s', ColumnTypeInterface::class)
            );
        }

        $this->registry[mb_strtolower($type)] = $className;
    }

    /**
     * @inheritDoc
     */
    public function has(string $type): bool
    {
        return array_key_exists(mb_strtolower($type), $this->registry);
    }

    /**
     * @inheritDoc
     */
    public function remove(string $type): bool
    {
        if (!$this->has($type)) {
            return false;
        }

        unset($this->registry[mb_strtolower($type)]);

        return true;
    }
}
