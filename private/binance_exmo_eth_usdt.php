<?php

use Src\Exchanges\Binance;
use Src\Exchanges\Exmo;
use Src\Start;

require_once dirname(__DIR__) . '/index.php';

$symbol = 'ETH/USDT';

$assets = explode('/', $symbol);

Start::setProfitVolume(0);

Start::start(
    new Binance($assets, fee: 0.09),
    new Exmo($assets, fee: 0.03),
    $symbol
);