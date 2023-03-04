<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql;

use Fi1a\DB\Exceptions\QueryErrorException;
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
}
