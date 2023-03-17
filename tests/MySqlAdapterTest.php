<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Query;
use Fi1a\DB\Facades\Schema;
use Fi1a\DB\Queries\Column;
use Fi1a\DB\Queries\ColumnType;
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
     * Вставка значений
     *
     * @depends testCreateTableWithIndex
     */
    public function testInsert(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::insert()
            ->name('tableNameForeign')
            ->column(
                ColumnType::create()
                    ->name('id')
                    ->integer()
            )
            ->rows([['id' => 1], ['id' => 2], ['id' => 3]]);

        $this->assertTrue($adapter->exec($query));

        $adapter = $this->getAdapter();

        $query = Query::insert()
            ->name('tableName')
            ->column(
                ColumnType::create()
                    ->name('primary')
                    ->integer()
            )
            ->column(
                ColumnType::create()
                    ->name('index')
                    ->integer()
            )
            ->column(
                ColumnType::create()
                    ->name('unique')
                    ->integer()
            )
            ->column(
                ColumnType::create()
                    ->name('foreign')
                    ->integer()
            )
            ->rows([
                [
                    'primary' => null,
                    'index' => 1,
                    'unique'  => 1,
                    'foreign' => 1,
                ],
                [
                    'primary' => null,
                    'index' => 2,
                    'unique'  => 2,
                    'foreign' => 2,
                ],
                [
                    'primary' => null,
                    'index' => 3,
                    'unique'  => 3,
                    'foreign' => 3,
                ],
            ]);

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Запрс выборки
     *
     * @depends testInsert
     */
    public function testSelect(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName')
            ->column(ColumnType::create()->name('primary')->integer())
            ->where('primary', '=', 1);

        $items = $adapter->query($query);

        $this->assertCount(1, $items);
        $this->assertEquals([
            [
                'primary' => 1,
            ],
        ], $items);
    }

    /**
     * Запрс выборки
     *
     * @depends testInsert
     */
    public function testSelectWithAlias(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName')
            ->column(ColumnType::create()->name('primary')->integer(), 'alias')
            ->where('primary', '=', 1);

        $items = $adapter->query($query);

        $this->assertCount(1, $items);
        $this->assertEquals([
            [
                'alias' => 1,
            ],
        ], $items);
    }

    /**
     * Запрс выборки всех полей
     *
     * @depends testInsert
     */
    public function testSelectAll(): void
    {
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('tableName');

        $items = $adapter->query($query);

        $this->assertCount(3, $items);
        $this->assertEquals([
            [
                'primary' => '1',
                'index' => '1',
                'unique' => '1',
                'foreign' => '1',
            ],
            [
                'primary' => '2',
                'index' => '2',
                'unique' => '2',
                'foreign' => '2',
            ],
            [
                'primary' => '3',
                'index' => '3',
                'unique' => '3',
                'foreign' => '3',
            ],
        ], $items);
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
     * Добавление колонок таблицы
     *
     * @depends testCreateTableWithIndex
     */
    public function testAddColumn(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::alter()
            ->name('tableNameAddIndex')
            ->addColumn(Column::create()
                ->name('addPrimary')
                ->integer()
                ->primary(true))
            ->addColumn(Column::create()
                ->name('addUnique')
                ->integer()
                ->default(100)
                ->unique())
            ->addColumn(Column::create()
                ->name('addIndex')
                ->integer()
                ->nullable()
                ->index())
            ->addColumn(Column::create()
                ->name('addForeign')
                ->foreign(
                    'tableNameForeign',
                    'id',
                    ForeignIndexInterface::CASCADE,
                    ForeignIndexInterface::CASCADE
                ));

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Добавление первичного ключа
     *
     * @depends testCreateTable
     */
    public function testChangeColumn(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::alter()
            ->name('tableNameAddIndex')
            ->changeColumn(Column::create()
                ->name('addPrimary')
                ->rename('changePrimary')
                ->bigInteger())
            ->changeColumn(Column::create()
                ->name('addUnique')
                ->rename('changeUnique')
                ->nullable()
                ->char(50))
            ->changeColumn(Column::create()
                ->name('addIndex')
                ->boolean())
            ->changeColumn(Column::create()
                ->name('addForeign')
                ->rename('changeForeign'));

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Удаление колонок
     *
     * @depends testCreateTable
     */
    public function testDropColumns(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::alter()
            ->name('tableNameAddIndex')
            ->dropColumn('unique', 'index', 'foreign');

        $this->assertTrue($adapter->exec($query));
    }

    /**
     * Удаление колонок
     *
     * @depends testCreateTable
     */
    public function testRenameTable(): void
    {
        $adapter = $this->getAdapter();

        $query = Schema::rename()
            ->name('tableNameAddIndex')
            ->newName('tableNameRename');

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
            ->name('tableNameRename');

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

    /**
     * Исключение при ошибке в запросе
     */
    public function testQueryException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();

        $query = Query::select()
            ->from('notExists');

        $adapter->query($query);
    }
}
