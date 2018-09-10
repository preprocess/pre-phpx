<?php

return [
    [
        "type" => "literal",
        "value" => trim("
function Label(\$props) {
    return (
        "),
    ],
    [
        "type" => "tag",
        "value" => "<div className={0}>",
        "started" => 64,
        "attributes" => [
            [
                [
                    "type" => "literal",
                    "value" => "\"outer\"",
                ],
            ],
        ],
        "name" => "div",
        "children" => [
            [
                "type" => "expression",
                "value" => [
                    [
                        "type" => "literal",
                        "value" => "\$props->prefix",
                    ],
                ],
                "started" => 79,
            ],
            [
                "type" => "tag",
                "value" => "<div className={0}>",
                "started" => 97,
                "attributes" => [
                    [
                        [
                            "type" => "literal",
                            "value" => "\"inner\"",
                        ],
                    ]
                ],
                "name" => "div",
                "children" => [
                    [
                        "type" => "expression",
                        "value" => [
                            [
                                "type" => "literal",
                                "value" => "\$props->text",
                            ],
                        ],
                        "started" => 110,
                    ],
                ],
            ],
            [
                "type" => "expression",
                "value" => [
                    [
                        "type" => "literal",
                        "value" => "\$props->suffix",
                    ],
                ],
                "started" => 130,
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
