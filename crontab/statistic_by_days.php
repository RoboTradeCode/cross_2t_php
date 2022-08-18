<?php

require_once dirname(__DIR__) . '/index.php';

for ($i = 0; $i < 10; $i++) {

    try {

        sleep($i);

        //Write code start

        //Write code end

        break;

    } catch (Exception $e) {

        continue;

    }

}
