<?php

namespace Src;

class Comparator
{

    private Exchange $exchange_one;
    private Exchange $exchange_two;
    private string $symbol;
    private array $orderbook_one;
    private array $orderbook_two;

    public function __construct(Exchange $exchange_one, Exchange $exchange_two, string $symbol)
    {

        $this->exchange_one = $exchange_one;

        $this->exchange_two = $exchange_two;

        $this->symbol = $symbol;

    }

    public function run(): array
    {

        $time_start = microtime(true);

        $this->orderbook_one = $this->exchange_one->getFilterOrderBookByMinTradeAmount($this->symbol);

        $metrics['get_orderbook'][$this->exchange_one->name] = round(microtime(true) - $time_start, 6);

        $time_start = microtime(true);

        $this->orderbook_two = $this->exchange_two->getFilterOrderBookByMinTradeAmount($this->symbol);

        $metrics['get_orderbook'][$this->exchange_two->name] = round(microtime(true) - $time_start, 6);

        if ($this->orderbook_one && $this->orderbook_two)
            return [
                'results' => [
                    'direct_result' => $this->countAmountAndProfit(),
                    'reverse_result' => $this->countAmountAndProfit(false),
                ],
                'metrics' => $metrics
            ];

        return [];

    }

    public function countAmountAndProfit(bool $is_exchange_one_seller = true): array
    {

        if ($is_exchange_one_seller) {

            $exchange_sell = $this->exchange_one;
            $exchange_buy = $this->exchange_two;

            $orderbook_sell = $this->orderbook_one;
            $orderbook_buy = $this->orderbook_two;

        } else {

            $exchange_sell = $this->exchange_two;
            $exchange_buy = $this->exchange_one;

            $orderbook_sell = $this->orderbook_two;
            $orderbook_buy = $this->orderbook_one;

        }

        $exchange_sell_price = $orderbook_sell['bids']['price'];

        $exchange_sell_base_asset_amount_give = $this->getAmount($orderbook_sell, $orderbook_buy);

        $exchange_sell_quote_asset_amount_get_before_fee = $exchange_sell_price * $exchange_sell_base_asset_amount_give;

        $exchange_sell_quote_asset_amount_get = $exchange_sell_quote_asset_amount_get_before_fee * (1 - $exchange_sell->fee / 100);


        $exchange_buy_price = $orderbook_buy['asks']['price'];

        $exchange_buy_base_asset_amount_get_before_fee = $exchange_buy->ccxtFloor($exchange_sell_quote_asset_amount_get / $exchange_buy_price, $this->symbol, 'amount');

        $exchange_buy_base_asset_amount_get = $exchange_buy_base_asset_amount_get_before_fee * (1 - $exchange_buy->fee / 100);

        $exchange_buy_quote_asset_amount_give = $exchange_buy_base_asset_amount_get_before_fee * $exchange_buy_price;


        // profit = floor(x1*p1/p2*(1-fee1)) * (1-fee2) - x1
        $profit = $exchange_buy_base_asset_amount_get - $exchange_sell_base_asset_amount_give;

        return [
            'exchange_sell_name' => $exchange_sell->name,
            'exchange_buy_name' => $exchange_buy->name,
            'profit' => floor($profit * 10**8) / 10**8,
            'profit_volume' => round($profit / $exchange_sell_base_asset_amount_give * 100, 2),
            'sell' => [
                'price' => $exchange_sell_price,
                'base_asset' => -1 * $exchange_sell_base_asset_amount_give,
                'quote_asset_before_fee' => $exchange_sell_quote_asset_amount_get_before_fee,
                'quote_asset' => $exchange_sell_quote_asset_amount_get
            ],
            'buy' => [
                'price' => $exchange_buy_price,
                'base_asset' => $exchange_buy_base_asset_amount_get,
                'base_asset_before_fee' => $exchange_buy_base_asset_amount_get_before_fee,
                'quote_asset' => -1 * $exchange_buy_quote_asset_amount_give,
            ]
        ];

    }

    public function recordResearchToDb(array $result, string $symbol, float $execution_full_time): void
    {

        DB::recordResearch(
            $result['exchange_sell_name'],
            $result['exchange_buy_name'],
            $symbol,
            $result['profit'],
            $result['profit_volume'],
            $result['sell']['price'],
            $result['sell']['base_asset'],
            $result['sell']['quote_asset_before_fee'],
            $result['sell']['quote_asset'],
            $result['buy']['price'],
            $result['buy']['base_asset'],
            $result['buy']['base_asset_before_fee'],
            $result['buy']['quote_asset'],
            $execution_full_time
        );

    }

    private function getAmount(array $orderbook_sell, array $orderbook_buy): float
    {

        return min($orderbook_sell['bids']['amount'], $orderbook_buy['asks']['amount']);

    }

}