<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Handlers\Expressions;

use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\DB\Queries\ColumnType;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Условие "является ли значение null или boolean"
 */
class IsExpressionTest extends TestCase
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
                ->name('columnNull')
                ->bigInteger())
            ->where('columnNull', 'is', null);

        $items = $adapter->query($query);

        $this->assertCount(3, $items);
        $this->assertEquals([
            [
                'columnNull' => null,
            ],
            [
                'columnNull' => null,
            ],
            [
                'columnNull' => null,
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
                ->name('columnNull')
                ->bigInteger(), 'alias')
            ->where('alias', 'is', null);

        $items = $adapter->query($query);

        $this->assertCount(3, $items);
        $this->assertEquals([
            [
                'alias' => null,
            ],
            [
                'alias' => null,
            ],
            [
                'alias' => null,
            ],
        ], $items);
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
            ->where(1, 'is', null);

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
