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

        $query = Schema::create()
            ->name('tableNameAddIndex')
            ->column(
                Column::create()
                    ->name('primary')
                    ->integer()
            )
            ->column(
                Column::create()
                    ->name('index')
                    ->integer()
            )
            ->column(
                Column::create()
                    ->name('unique')
                    ->integer()
            )
            ->column(
                Column::create()
                    ->name('foreign')
                    ->integer()
            );

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Добавление первичного ключа
     *
     * @depends testCreateTable
     */
    public function testAddPrimaryIndex(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::addIndex()
            ->primary()
            ->column('primary')
            ->table('tableNameAddIndex')
            ->increments();

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Добавление индекса
     *
     * @depends testCreateTable
     */
    public function testAddIndex(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::addIndex()
            ->index()
            ->column('index')
            ->table('tableNameAddIndex');

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Добавление уникального ключа
     *
     * @depends testCreateTable
     */
    public function testAddUniqueIndex(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::addIndex()
            ->unique()
            ->column('unique')
            ->table('tableNameAddIndex');

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Добавление уникального ключа
     *
     * @depends testCreateTable
     */
    public function testAddForeignIndex(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::addIndex()
            ->foreign()
            ->column('foreign')
            ->table('tableNameAddIndex')
            ->on('tableNameForeign')
            ->reference('id')
            ->onDelete(ForeignIndexInterface::CASCADE)
            ->onUpdate(ForeignIndexInterface::CASCADE);

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Удаление первичного ключа
     *
     * @depends testAddPrimaryIndex
     */
    public function testDropPrimaryIndex(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::dropIndex()
            ->table('tableNameAddIndex')
            ->primary();

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Удаление индекса
     *
     * @depends testAddIndex
     */
    public function testDropIndex(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::dropIndex()
            ->table('tableNameAddIndex')
            ->index('ixIndex');

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Удаление уникального индекса
     *
     * @depends testAddUniqueIndex
     */
    public function testDropUniqueIndex(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::dropIndex()
            ->table('tableNameAddIndex')
            ->unique('ixUnique');

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Удаление внешнего ключа
     *
     * @depends testAddForeignIndex
     */
    public function testDropForeignIndex(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::dropIndex()
            ->table('tableNameAddIndex')
            ->foreign('ixForeign');

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Создание таблицы с типом integer
     *
     * @depends testCreateTable
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
            ->name('tableNameAddIndex');

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
