<?php

declare(strict_types=1);

namespace Fi1a\MySql\ColumnTypes;

use const JSON_UNESCAPED_UNICODE;

/**
 * Json
 */
class JsonType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'JSON';
    }

    /**
     * @inheritDoc
     */
    public function conversionTo($value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        return $this->connection->quote(json_encode($value, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @inheritDoc
     */
    public function conversionFrom(string $value)
    {
        return json_decode($value, true);
    }
}
