<?php

return [
    trim("
function Hello(\$props) {
    return
    "),
    [
        "tag" => "<div>",
        "started" => 40,
    ],
    "Hello",
    [
        "expression" => "\$props->name",
        "started" => 59,
    ],
    [
        "tag" => "</div>",
        "started" => 64,
    ],
    trim("
    ;
}
    "),
];
