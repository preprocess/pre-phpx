<?php

return [
    [
        "type" => "literal",
        "value" => trim("
function Hello(\$props) {
    return
        "),
    ],
    [
        "type" => "tag",
        "value" => "<div thing={0}>",
        "started" => 52,
        "attributes" => [
            [
                [
                    "type" => "tag",
                    "value" => "<span>",
                    "started" => 7,
                ],
                [
                    "type" => "tag",
                    "value" => "</span>",
                ],
            ],
        ],
    ],
    [
        "type" => "tag",
        "value" => "</div>",
    ],
    [
        "type" => "literal",
        "value" => trim("
    ;
}
        "),
    ],
];
