<?php

return [
    trim("
function Hello(\$props) {
    return
    "),
    [
        "tag" => "<div className='error'>",
        "started" => 58,
    ],
    "You forgot a field",
    [
        "tag" => "</div>",
        "started" => 81,
    ],
    trim("
    ;
}
    "),
];
