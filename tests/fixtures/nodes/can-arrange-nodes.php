<?php

return [
    [
        "type" => "literal",
        "value" => trim("
function Error(\$props) {
    return (
        "),
    ],
    [
        "type" => "tag",
        "value" => "<div render={0}>",
        "started" => 61,
        "attributes" => [
            [
                [
                    "type" => "literal",
                    "value" => "\$error ~>",
                ],
                [
                    "type" => "tag",
                    "value" => "<span className={0}>",
                    "attributes" => [
                        [
                            [
                                "type" => "literal",
                                "value" => "\"error\"",
                            ],
                        ],
                    ],
                    "children" => [
                        [
                            "type" => "expression",
                            "value" => [
                                [
                                    "type" => "literal",
                                    "value" => "\$error",
                                ],
                            ],
                            "started" => 36,
                        ],
                    ],
                    "name" => "span",
                    "started" => 29,
                ],
            ],
        ],
        "name" => "div",
        "children" => [
            [
                "type" => "literal",
                "value" => "You forgot the",
                "started" => 15,
            ],
            [
                "type" => "expression",
                "value" => [
                    [
                        "type" => "literal",
                        "value" => "\$props->name",
                    ],
                ],
                "started" => 89,
            ],
            [
                "type" => "literal",
                "value" => "field",
            ],
        ],
    ],
    [
        "type" => "literal",
        "value" => trim("
    );
}
        "),
    ],
];
