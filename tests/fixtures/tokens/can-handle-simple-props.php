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
                    "value" => "\"error\"",
                ],
            ],
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
