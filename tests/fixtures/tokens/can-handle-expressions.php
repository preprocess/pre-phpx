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
        "value" => "<div>",
        "started" => 40,
    ],
    [
        "type" => "literal",
        "value" => "Hello",
        "started" => 6,
    ],
    [
        "type" => "expression",
        "value" => [
            [
                "type" => "literal",
                "value" => "\$props->name",
            ],
        ],
        "started" => 59,
    ],
    [
        "type" => "tag",
        "value" => "</div>",
        "started" => 64,
    ],
    [
        "type" => "literal",
        "value" => trim("
    ;
}
        "),
    ],
];
