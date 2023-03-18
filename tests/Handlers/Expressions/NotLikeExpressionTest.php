<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Handlers\Expressions;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\DB\Queries\ColumnType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Условие "not like"
 */
class NotLikeExpressionTest extends TestCase
{
    /**
     * Создание таблицы с типом
     */
    public function testCreateTableWithType(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::create()
            ->name('tableName')
            ->column(
                Column::create()
                    ->name('column')
                    ->text()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->text()
                    ->nullable()
            );

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Вставка значений
     *
     * @depends testCreateTableWithType
     */
    public function testInsertWithType(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::insert()
            ->name('tableName')
            ->column(
                Column::create()
                    ->name('column')
                    ->text()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->text()
            );

        $query->rows([
            [
                'column' => 'foo bar baz',
                'columnNull' => null,
            ],
            [
                'column' => 'foo bar qux',
                'columnNull' => null,
            ],
            [
                'column' => 'foo bar quz',
                'columnNull' => null,
            ],
        ]);

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Условие
     */
    public function testExpression(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName', 'tableAlias')
            ->column(ColumnType::create()
                ->name('column')
                ->text())
            ->where('column', 'not like', '%bar%');

        $items = $adapter->query($query);

        $this->assertCount(0, $items);
    }

    /**
     * Условие
     */
    public function testAliasExpression(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName')
            ->column(ColumnType::create()
                ->name('column')
                ->text(), 'alias')
            ->where('alias', 'not like', '%bar%');

        $items = $adapter->query($query);

        $this->assertCount(0, $items);
    }

    /**
     * Условие
     */
    public function testValueExpression(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName', 'tableAlias')
            ->column(ColumnType::create()
                ->name('column')
                ->text())
            ->where('foo bar baz', 'not like', '%bar%');

        $items = $adapter->query($query);

        $this->assertCount(0, $items);
    }

    /**
     * Условие
     */
    public function testColumnExpression(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName', 'tableAlias')
            ->column(ColumnType::create()
                ->name('column')
                ->text())
            ->where(
                ColumnType::create()->name('column'),
                'not like',
                ColumnType::create()->name('column')
            );

        $items = $adapter->query($query);

        $this->assertCount(0, $items);
    }

    /**
     * Удаление таблицы с типом
     *
     * @depends testCreateTableWithType
     */
    public function testDropTableWithType(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::drop()
            ->name('tableName');

        $this->assertTrue($adapter->exec($query));
    }
}
