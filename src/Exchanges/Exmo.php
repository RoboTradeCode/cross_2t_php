<?php

namespace Src\Exchanges;

use Src\Exchange;

class Exmo extends Exchange
{

    public function __construct(array $assets, array $ccxt_settings = [], string $name = 'exmo', int $depth = 5, float $fee = 0.3, float $min_trade_usdt_amounts = 20)
    {

        parent::__construct($assets, $ccxt_settings, $name, $depth, $fee, $min_trade_usdt_amounts);

    }

}