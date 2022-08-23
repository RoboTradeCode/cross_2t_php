<?php

use Src\Exchanges\Binance;
use Src\Exchanges\Exmo;
use Src\Start;

require_once dirname(__DIR__) . '/index.php';

$symbol = 'BTC/USDT';

$assets = explode('/', $symbol);

Start::setProfitVolume(0);

Start::start(
    new Binance($assets, ['enableRateLimit' => false], fee: 0.01),
    new Exmo($assets, ['enableRateLimit' => false], fee: 0.03),
    $symbol
);