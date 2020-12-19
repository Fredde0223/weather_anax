<?php

return [
    "routes" => [
        [
            "info" => "Weather service (API)",
            "mount" => "diapi",
            "handler" => "\Fredde\DIController\DIApiController",
        ],
    ]
];
