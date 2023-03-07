<?php

declare(strict_types=1);

use Fi1a\DI\Builder;
use Fi1a\MySql\ColumnTypes\BigIntegerType;
use Fi1a\MySql\ColumnTypes\BinaryType;
use Fi1a\MySql\ColumnTypes\BooleanType;
use Fi1a\MySql\ColumnTypes\CharType;
use Fi1a\MySql\ColumnTypes\DateTimeType;
use Fi1a\MySql\ColumnTypes\DateType;
use Fi1a\MySql\ColumnTypes\DecimalType;
use Fi1a\MySql\ColumnTypes\DoubleType;
use Fi1a\MySql\ColumnTypes\EnumType;
use Fi1a\MySql\ColumnTypes\FloatType;
use Fi1a\MySql\ColumnTypes\IntegerType;
use Fi1a\MySql\ColumnTypes\JsonType;
use Fi1a\MySql\ColumnTypes\LongTextType;
use Fi1a\MySql\ColumnTypes\MediumIntegerType;
use Fi1a\MySql\ColumnTypes\MediumTextType;
use Fi1a\MySql\ColumnTypes\Registry;
use Fi1a\MySql\ColumnTypes\RegistryInterface;
use Fi1a\MySql\ColumnTypes\SmallIntegerType;
use Fi1a\MySql\ColumnTypes\StringType;
use Fi1a\MySql\ColumnTypes\TextType;
use Fi1a\MySql\ColumnTypes\TimeType;
use Fi1a\MySql\ColumnTypes\TimestampType;
use Fi1a\MySql\ColumnTypes\TinyIntegerType;
use Fi1a\MySql\Facades\Registry as RegistryFacade;

di()->config()->addDefinition(
    Builder::build(RegistryInterface::class)
        ->defineFactory(function () {
            static $instance;

            // @codeCoverageIgnoreStart
            if ($instance === null) {
                $instance = new Registry();
            }
            // @codeCoverageIgnoreEnd

            return $instance;
        })
        ->getDefinition()
);

RegistryFacade::add('integer', IntegerType::class);
RegistryFacade::add('bigInteger', BigIntegerType::class);
RegistryFacade::add('tinyInteger', TinyIntegerType::class);
RegistryFacade::add('smallInteger', SmallIntegerType::class);
RegistryFacade::add('mediumInteger', MediumIntegerType::class);
RegistryFacade::add('timestamp', TimestampType::class);
RegistryFacade::add('char', CharType::class);
RegistryFacade::add('string', StringType::class);
RegistryFacade::add('text', TextType::class);
RegistryFacade::add('mediumText', MediumTextType::class);
RegistryFacade::add('longText', LongTextType::class);
RegistryFacade::add('time', TimeType::class);
RegistryFacade::add('dateTime', DateTimeType::class);
RegistryFacade::add('date', DateType::class);
RegistryFacade::add('decimal', DecimalType::class);
RegistryFacade::add('double', DoubleType::class);
RegistryFacade::add('float', FloatType::class);
RegistryFacade::add('binary', BinaryType::class);
RegistryFacade::add('boolean', BooleanType::class);
RegistryFacade::add('json', JsonType::class);
RegistryFacade::add('enum', EnumType::class);
