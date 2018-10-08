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
        "value" => "<div className={0}>",
        "started" => 54,
        "attributes" => [
            [
                [
                    "type" => "literal",
                    "value" => "\$error ~>",
                ],
                [
                    "type" => "tag",
                    "value" => "<span>",
                    "started" => 15,
                ],
                [
                    "type" => "expression",
                    "value" => [
                        [
                            "type" => "literal",
                            "value" => "\$error",
                        ],
                    ],
                    "started" => 22,
                ],
                [
                    "type" => "tag",
                    "value" => "</span>",
                    "started" => 28,
                ],
            ]
        ],
    ],
    [
        "type" => "literal",
        "value" => "You forgot a field",
    ],
    [
        "type" => "tag",
        "value" => "</div>",
        "started" => 77,
    ],
    [
        "type" => "literal",
        "value" => trim("
    ;
}
        "),
    ],
];
