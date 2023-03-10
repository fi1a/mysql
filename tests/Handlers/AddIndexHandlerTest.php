<?php

declare(strict_types=1);

namespace Fi1a\Unit\MySql\Handlers;

use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\Unit\MySql\TestCases\TestCase;

/**
 * Обработчик добавления индекса
 */
class AddIndexHandlerTest extends TestCase
{
    /**
     * Исключение при пустой структуре индекса
     */
    public function testStructureIndexException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec([
            'type' => 'addIndex',
        ]);
    }

    /**
     * Исключение при отсутствии типа индекса
     */
    public function testStructureIndexTypeException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec([
            'type' => 'addIndex',
            'index' => [],
        ]);
    }

    /**
     * Исключение при валидации индекса
     */
    public function testStructureValidationException(): void
    {
        $this->expectException(QueryErrorException::class);
        $adapter = $this->getAdapter();
        $adapter->exec([
            'type' => 'addIndex',
            'index' => [
                'type' => 'index',
            ],
        ]);
    }
}
