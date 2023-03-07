<?php

declare(strict_types=1);

namespace Fi1a\MySql\Handlers;

use Fi1a\DB\Adapters\HandlerInterface;
use PDO;

/**
 * Обработчик MySql
 */
abstract class AbstractMySqlHandler implements HandlerInterface
{
    /**
     * @var PDO
     */
    protected $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
}
