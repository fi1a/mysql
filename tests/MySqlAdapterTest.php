<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\DB\Queries\Indexes\ForeignIndexInterface;
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
     * Создание таблицы с типом integer
     */
    public function testCreateTable(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableNameForeign')
            ->column(
                Column::create()
                    ->name('id')
                    ->integer()
                    ->primary()
            );

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Создание таблицы с типом integer
     */
    public function testCreateTableWithIndex(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableName')
            ->column(
                Column::create()
                    ->name('primary')
                    ->integer()
                    ->primary()
                    ->increments()
            )
            ->column(
                Column::create()
                    ->name('index')
                    ->integer()
                    ->index()
            )
            ->column(
                Column::create()
                    ->name('unique')
                    ->integer()
                    ->unique()
            )
            ->column(
                Column::create()
                    ->name('foreign')
                    ->integer()
                    ->foreign(
                        'tableNameForeign',
                        'id',
                        ForeignIndexInterface::CASCADE,
                        ForeignIndexInterface::CASCADE
                    )
            );

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Создание таблицы
     *
     *  @depends testCreateTableWithIndex
     */
    public function testCreateTableIfNotExists(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableName')
            ->ifNotExists()
            ->column(Column::create()->name('integer'));

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Удаление таблицы
     *
     * @depends testCreateTableWithIndex
     */
    public function testDropTable(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::drop()
            ->name('tableName');

        $this->assertTrue($adapter->exec($query));

        $query = Schema::drop()
            ->name('tableNameForeign');

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

    /**
     * Исключение при ошибке в запросе
     */
    public function testExecException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();

        $query = Schema::drop()
            ->name('tableName');

        $adapter->exec($query);
    }
}
