<?php

namespace Src;

use Exception;
use LogicException;

abstract class Exchange
{


    public string $name;
    public int $depth;
    public float $fee;
    public array $assets;
    public float $min_trade_usdt_amounts;
    public array $min_trade_amounts;
    private \ccxt\Exchange $exchange;

    public function __construct(array $assets, array $ccxt_settings, string $name, int $depth, float $fee, float $min_trade_usdt_amounts)
    {

        $this->name = $name;

        $this->depth = $depth;

        $this->fee = $fee;

        $this->assets = $assets;

        $this->min_trade_usdt_amounts = $min_trade_usdt_amounts;

        $this->createExchange($ccxt_settings);

        $this->minCryptoAmountTrade();

    }

    public function getOrderbook(string $symbol): array
    {

        try {

            return $this->exchange->fetch_order_book($symbol, $this->depth);

        } catch (Exception $e) {

            echo '[' . date('Y-m-d H:i:s') . '] [ERROR] Can not get orderbook for symbol: ' . $symbol . '. Error: ' . $e->getMessage() . PHP_EOL;

            DB::error($e->getMessage());

        }

        return [];

    }

    public function getFilterOrderBookByMinTradeAmount(string $symbol): array
    {

        list($base_asset) = explode('/', $symbol);

        $filter_orderbook = $orderbook = $this->getOrderbook($symbol);

        foreach (['bids', 'asks'] as $item) {

            unset($filter_orderbook[$item]);

            foreach ($orderbook[$item] as $glass) {

                if ($glass[1] > $this->min_trade_amounts[$base_asset]) {

                    $filter_orderbook[$item] = [
                        'price' => $glass[0],
                        'amount' => $glass[1]
                    ];

                    break;

                }

            }

        }

        return (isset($filter_orderbook['bids']) && isset($filter_orderbook['asks']))
            ? $filter_orderbook
            : [];

    }

    public function minCryptoAmountTrade(): void
    {

        foreach ($this->assets as $asset) {

            if ($asset == 'USDT') {

                $this->min_trade_amounts[$asset] = $this->min_trade_usdt_amounts;

            } else {

                $symbol = $asset . '/USDT';

                if (isset($this->exchange->markets[$symbol])) {

                    $glass = $this->getOrderbook($symbol);

                    $this->min_trade_amounts[$asset] = $this->ccxtFloor(2 * $this->min_trade_usdt_amounts / ($glass['bids'][0][0] + $glass['asks'][0][0]), $symbol, 'amount');

                } else {

                    echo '[' . date('Y-m-d H:i:s') . '] [ERROR] No such market: ' . $symbol . PHP_EOL;

                }

            }

        }

    }

    public function ccxtFloor(float $number, string $symbol, string $precision_name): float
    {

        $increment = $this->getPrecision($symbol, $precision_name);

        return (is_int($increment)) ? (floor($number * 10**$increment) / 10**$increment) : ($increment * floor($number / $increment));

    }

    private function getPrecision(string $symbol, string $precision_name)
    {

        return $this->exchange->markets[$symbol]['precision'][$precision_name];

    }

    private function createExchange(array $ccxt_settings): void
    {

        $this->exchange = new ('\\ccxt\\' . $this->name)($ccxt_settings);

        $this->exchange->load_markets();

    }

}