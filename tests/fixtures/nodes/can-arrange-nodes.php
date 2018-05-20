<?php

return [
    [
        "text" => trim("
function Error(\$props) {
    return (
        "),
    ],
    [
        "tag" => "<div render={0}>",
        "started" => 61,
        "attributes" => [
            [
                "tag" => "<span className=\"error\">",
                "started" => 34,
                "name" => "span",
                "children" => [
                    [
                        "expression" => "\$error",
                        "started" => 41,
                    ],
                ],
            ],
        ],
        "name" => "div",
        "children" => [
            [
                "text" => "You forgot the",
            ],
            [
                "expression" => "\$props->name",
                "started" => 89,
            ],
            [
                "text" => "field",
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
