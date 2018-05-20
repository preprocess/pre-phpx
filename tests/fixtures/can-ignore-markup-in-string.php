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
    [
        "expression" => "'<div>this div should show</div>'",
        "started" => 74,
    ],
    [
        "tag" => "</div>",
        "started" => 79,
    ],
    trim("
    ;
}
    "),
];
