<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\DB\Facades\Query;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Обработчик вставки
 */
class InsertHandlerTest extends TestCase
{
    /**
     * Исключение
     */
    public function testNoValid(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec(Query::insert());
    }
}
