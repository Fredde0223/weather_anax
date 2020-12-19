<?php

return [
    "routes" => [
        [
            "info" => "Weather service",
            "mount" => "di",
            "handler" => "\Fredde\DIController\DIController",
        ],
    ]
];
