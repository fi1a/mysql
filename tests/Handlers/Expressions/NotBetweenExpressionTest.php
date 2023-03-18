<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Handlers\Expressions;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\DB\Queries\ColumnType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Условие "NOT BETWEEN"
 */
class NotBetweenExpressionTest extends TestCase
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
                    ->bigInteger()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->bigInteger(true)
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->bigInteger()
                    ->nullable()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->bigInteger()
                    ->nullable()
                    ->default(100)
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
                    ->bigInteger()
            )
            ->column(
                Column::create()
                    ->name('columnUnsigned')
                    ->bigInteger()
            )
            ->column(
                Column::create()
                    ->name('columnNull')
                    ->bigInteger()
            )
            ->column(
                Column::create()
                    ->name('columnDefault')
                    ->bigInteger()
            );

        $query->rows([
            [
                'column' => 1,
                'columnUnsigned' => 1,
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 2,
                'columnUnsigned' => 2,
                'columnNull' => null,
                'columnDefault' => null,
            ],
            [
                'column' => 3,
                'columnUnsigned' => 3,
                'columnNull' => null,
                'columnDefault' => null,
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
                ->bigInteger())
            ->where('column', 'not between', [1, 2]);

        $items = $adapter->query($query);

        $this->assertCount(1, $items);
        $this->assertEquals([
            [
                'column' => 3,
            ],
        ], $items);
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
                ->bigInteger(), 'alias')
            ->where('alias', 'not between', [1, 2]);

        $items = $adapter->query($query);

        $this->assertCount(1, $items);
        $this->assertEquals([
            [
                'alias' => 3,
            ],
        ], $items);
    }

    /**
     * Условие
     */
    public function testSecondColumnExpression(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName', 'tableAlias')
            ->column(ColumnType::create()
                ->name('column')
                ->bigInteger())
            ->where(1, 'not between', ColumnType::create()->name('column')->integer());

        $adapter->query($query);
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
                ->name('columnUnsigned')
                ->bigInteger(true))
            ->where(3, 'not between', [1, 2]);

        $items = $adapter->query($query);

        $this->assertCount(3, $items);
        $this->assertEquals([
            [
                'columnUnsigned' => 1,
            ],
            [
                'columnUnsigned' => 2,
            ],
            [
                'columnUnsigned' => 3,
            ],
        ], $items);
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
