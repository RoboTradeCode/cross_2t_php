<?php

namespace Src;

class Start
{

    private static float $profit_volume = 0;

    public static function setProfitVolume(float $profit_volume): void
    {

        self::$profit_volume = $profit_volume;

    }

    public static function start(Exchange $exchange_one, Exchange $exchange_two, string $symbol)
    {

        DB::connect();

        $comporator = new Comparator(
            $exchange_one,
            $exchange_two,
            $symbol
        );

        echo '[' . date('Y-m-d H:i:s') . '] [START] Start' . PHP_EOL;

        while (true) {

            sleep(1);

            $time_start = microtime(true);

            if ($results = $comporator->run()) {

                $execution_full_time = round(microtime(true) - $time_start, 6);

                foreach ($results['results'] as $result) {

                    if ($result['profit_volume'] > self::$profit_volume) {

                        $comporator->recordResearchToDb($result, $symbol, $execution_full_time);

                        echo '[' . date('Y-m-d H:i:s') . '] Profit: ' . $result['profit'] . PHP_EOL;

                    }

                }

            } else {

                echo '[' . date('Y-m-d H:i:s') . '] Empty results' . PHP_EOL;

                DB::error('Empty results in Comparator()->run()');

            }

        }

    }

}