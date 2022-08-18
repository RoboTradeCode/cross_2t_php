<?php

namespace Src\Exchanges;

use Src\Exchange;

class Binance extends Exchange
{

    public function __construct(array $assets, array $ccxt_settings = [], string $name = 'binance', int $depth = 5, float $fee = 0.1, float $min_trade_usdt_amounts = 20)
    {

        parent::__construct($assets, $ccxt_settings, $name, $depth, $fee, $min_trade_usdt_amounts);

    }

}