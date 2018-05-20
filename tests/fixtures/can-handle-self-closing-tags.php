<?php

return [
    trim("
function Hello(\$props) {
    return
    "),
    [
      "tag" => "<div thing={0}>",
      "started" => 52,
      "attributes" =>
        [
            [
                [
                    "tag" => "<span>",
                    "started" => 7,
                ],
                [
                    "tag" => "</span>",
                ],
            ],
        ],
    ],
    [
      "tag" => "</div>",
    ],
    trim("
    ;
}
    "),
];
