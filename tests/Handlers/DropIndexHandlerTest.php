<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Обработчик запроса удаления индекса
 */
class DropIndexHandlerTest extends TestCase
{
    /**
     * Исключение при валидации индекса
     */
    public function testStructureValidationException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec([
            'type' => 'dropIndex',
            'indexType' => 'unknown',
            'indexName' => 'indexName',
            'tableName' => 'tableName',
        ]);
    }
}
