<?php

return [
    [
        "text" => trim("
function Label(\$props) {
    return (
        "),
    ],
    [
        "tag" => "<div className={0}>",
        "started" => 64,
        "attributes" => [
            [
                "text" => "\"outer\"",
            ],
        ],
        "name" => "div",
        "children" => [
            [
                "expression" => "\$props->prefix",
                "started" => 79,
            ],
            [
                "tag" => "<div className={0}>",
                "started" => 97,
                "attributes" => [
                    [
                        "text" => "\"inner\"",
                    ],
                ],
                "name" => "div",
                "children" => [
                    [
                        "expression" => "\$props->text",
                        "started" => 110,
                    ],
                ],
            ],
            [
                "expression" => "\$props->suffix",
                "started" => 130,
            ],
        ],
    ],
    [
        "text" => trim("
    );
}
        "),
    ],
];
