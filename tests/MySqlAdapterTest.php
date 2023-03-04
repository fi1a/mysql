<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Адаптер MySql
 */
class MySqlAdapterTest extends TestCase
{
    /**
     * Исключение при не известном запросе
     */
    public function testQueryHandlerException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec(['type' => 'unknown']);
    }

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
     *
     *  @depends testCreateTable
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
     * Удаление таблицы
     *
     * @depends testCreateTable
     */
    public function testDropTable(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::drop()
            ->name('tableName');

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Удаление таблицы
     *
     * @depends testDropTable
     */
    public function testDropTableIfExists(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::drop()
            ->ifExists()
            ->name('tableName');

        $this->assertTrue($adapter->exec($query));
    }
}
