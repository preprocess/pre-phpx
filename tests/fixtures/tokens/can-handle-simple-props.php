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
                "\"error\""
            ],
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
