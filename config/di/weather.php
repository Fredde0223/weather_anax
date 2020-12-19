<?php
/**
 * Configuration file for weather service.
 */
return [
    "services" => [
        "weather" => [
            "shared" => true,
            "callback" => function () {
                $apiKeys = include(__DIR__ . '/../apikeys.php');
                $IPKey = $apiKeys["ipStack"];
                $OWKey = $apiKeys["openWeather"];
                $obj = new \Fredde\DI\DIWeather($IPKey, $OWKey);
                return $obj;
            }
        ],
    ],
];
