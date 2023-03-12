<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Schema;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Обработчик переименования таблицы
 */
class RenameTableHandlerTest extends TestCase
{
    /**
     * Исключение при пустом имени таблицы
     */
    public function testValidateEmptyTableName(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec(Schema::rename());
    }
}
