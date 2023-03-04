<?php

declare(strict_types=1);

namespace Fi1a\MySql;

use Fi1a\DB\Adapters\AbstractSqlAdapter;
use Fi1a\DB\Adapters\HandlerInterface;
use Fi1a\DB\Exceptions\QueryErrorException;
use Fi1a\MySql\Handlers\CreateTableHandler;
use PDO;

/**
 * Адаптер MySql
 */
class MySqlAdapter extends AbstractSqlAdapter
{
    /**
     * @var PDO
     */
    protected $connection;

    /**
     * @param array<string, mixed>|null $options
     */
    public function __construct(string $dsn, ?string $username = null, ?string $password = null, ?array $options = null)
    {
        $this->connection = new PDO($dsn, $username, $password, $options);
    }

    /**
     * @inheritDoc
     */
    public function execSql(string $sql)
    {
        return $this->connection->exec($sql);
    }

    /**
     * @inheritDoc
     */
    public function querySql(string $sql): array
    {
        /** @var array<array-key, array<string, string>>|false $items */
        $items = $this->connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $items === false ? [] : $items;
    }

    /**
     * @inheritDoc
     */
    protected function getHandler(string $type): HandlerInterface
    {
        switch ($type) {
            case 'createTable':
                return new CreateTableHandler();
        }

        throw new QueryErrorException(sprintf('Неизвестный запрос %s', $type));
    }
}
