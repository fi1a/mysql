<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Обработчик создания таблицы
 */
class CreateTableHandlerTest extends TestCase
{
    /**
     * Создание таблицы
     */
    public function testCreateTable(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableName')
            ->column(Column::create()->name('column1'));

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Создание таблицы
     */
    public function testCreateTableIfNotExists(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableName')
            ->ifNotExists()
            ->column(Column::create()->name('column1'));

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Исключение при пустом имени таблицы
     */
    public function testValidateEmptyTableName(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec(Schema::create());
    }

    /**
     * Исключение при пустых колонках
     */
    public function testValidateEmptyColumns(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec(Schema::create()->name('tableName'));
    }

    /**
     * Исключение при пустом имени колонки
     */
    public function testValidateEmptyColumnName(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec(
            Schema::create()
            ->name('tableName')
            ->column(Column::create())
        );
    }
}
