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
        "type" => "expression",
        "value" => [
            [
                "type" => "literal",
                "value" => "\"<div>this div should show</div>\"",
            ],
        ],
        "started" => 74,
    ],
    [
        "type" => "tag",
        "value" => "</div>",
        "started" => 79,
    ],
    [
        "type" => "literal",
        "value" => trim("
    ;
}
        "),
    ],
];
