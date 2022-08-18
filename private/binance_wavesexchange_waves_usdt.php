<?php

use Src\Exchanges\Binance;
use Src\Exchanges\WavesExchange;
use Src\Start;

require_once dirname(__DIR__) . '/index.php';

$symbol = 'WAVES/USDT';

$assets = explode('/', $symbol);

Start::setProfitVolume(0);

Start::start(
    new Binance($assets, fee: 0.09),
    new WavesExchange($assets, fee: 0.06),
    $symbol
);
