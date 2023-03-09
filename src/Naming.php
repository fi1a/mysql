<?php

declare(strict_types=1);

namespace Fi1a\MySql;

/**
 * Именование таблиц и колонок
 */
class Naming implements NamingInterface
{
    /**
     * @var string|null
     */
    protected $prefix;

    public function __construct(?string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @inheritDoc
     */
    public function wrapTableName(string $tableName): string
    {
        if (!$tableName) {
            return $tableName;
        }

        $explode = explode('.', $tableName);
        $database = null;
        $tableName = null;
        if (count($explode) === 1) {
            $tableName = $explode[0];
        }
        if (count($explode) > 1) {
            $database = $explode[0];
            $tableName = $explode[1];
        }
        if ($database) {
            $database = trim($database, ' `');
        }
        if ($tableName) {
            $tableName = trim($tableName, ' `');
            if ($this->prefix) {
                $tableName = $this->prefix . $tableName;
            }
        }

        return $database ? "`$database`.`$tableName`" : "`$tableName`";
    }

    /**
     * @inheritDoc
     */
    public function wrapColumnName(string $columnName): string
    {
        if (!$columnName) {
            return $columnName;
        }
        $explode = explode('.', $columnName);
        $database = null;
        $tableName = null;
        $columnName = null;
        if (count($explode) === 1) {
            $columnName = $explode[0];
        }
        if (count($explode) === 2) {
            $tableName = $explode[0];
            $columnName = $explode[1];
        }
        if (count($explode) > 2) {
            $database = $explode[0];
            $tableName = $explode[1];
            $columnName = $explode[2];
        }
        if ($tableName) {
            $tableName = trim($tableName, ' `');
            if ($this->prefix) {
                $tableName = $this->prefix . $tableName;
            }
        }
        if ($columnName) {
            $columnName = trim($columnName, ' `');
        }
        if ($database) {
            $database = trim($database, ' `');

            return "`$database`.`$tableName`.`$columnName`";
        }
        if ($tableName) {
            return "`$tableName`.`$columnName`";
        }

        return "`$columnName`";
    }
}
