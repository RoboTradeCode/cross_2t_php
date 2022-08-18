<?php

namespace Src;

use PDO;
use PDOException;

class DB
{

    private static PDO $connect;

    public static function connect(): void
    {

        $db = require_once CONFIG . '/db.config.php';

        try {

            $dbh = new PDO(
                'mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['db'],
                $db['user'],
                $db['password'],
                [PDO::ATTR_PERSISTENT => true]
            );

            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            echo '[' . date('Y-m-d H:i:s') . '] [ERROR] Can not connect to db. Message: ' . $e->getMessage() . PHP_EOL;

            die();

        }

        self::$connect = $dbh;

    }

    public static function error(string $message): void
    {

        self::insert('cross_2t_php_error', ['message' => $message]);

    }

    public static function recordResearch(
        string $exchange_sell,
        string $exchange_buy,
        string $symbol,
        float $profit,
        float $profit_volume,
        float $sell_price,
        float $sell_base_asset,
        float $sell_quote_asset_before_fee,
        float $sell_quote_asset,
        float $buy_price,
        float $buy_base_asset,
        float $buy_base_asset_before_fee,
        float $buy_quote_asset,
        float $execution_full_time
    ): void
    {

        $columns_and_values = [
            'exchange_sell' => $exchange_sell,
            'exchange_buy' => $exchange_buy,
            'symbol' => $symbol,
            'profit' => $profit,
            'profit_volume' => $profit_volume,
            'sell_price' => $sell_price,
            'sell_base_asset' => $sell_base_asset,
            'sell_quote_asset_before_fee' => $sell_quote_asset_before_fee,
            'sell_quote_asset' => $sell_quote_asset,
            'buy_price' => $buy_price,
            'buy_base_asset' => $buy_base_asset,
            'buy_base_asset_before_fee' => $buy_base_asset_before_fee,
            'buy_quote_asset' => $buy_quote_asset,
            'execution_full_time' => $execution_full_time
        ];

        self::insert('cross_2t_php_research', $columns_and_values);

    }


    private static function insert(string $table, array $columns_and_values): void
    {

        $columns = array_keys($columns_and_values);

        $sth = self::$connect->prepare(
            sprintf(
            /** @lang sql */ 'INSERT INTO `%s` (`%s`) VALUES (:%s)',
                $table,
                implode('`, `', $columns),
                implode(', :', $columns)
            )
        );

        $sth->execute($columns_and_values);

    }

}