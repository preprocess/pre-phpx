<?php

return [
    trim("
function Hello(\$props) {
    return
    "),
    [
        "tag" => "<div className={0}>",
        "started" => 54,
        "attributes" => [
            [
                "\$error ⇒",
                [
                    "tag" => "<span>",
                    "started" => 16,
                ],
                [
                    "expression" => "\$error",
                    "started" => 23,
                ],
                [
                    "tag" => "</span>",
                    "started" => 29,
                ],
            ]
        ],
    ],
    "You forgot a field",
    [
        "tag" => "</div>",
        "started" => 77,
    ],
    trim("
    ;
}
    "),
];
