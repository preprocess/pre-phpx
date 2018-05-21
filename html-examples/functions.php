<?php

namespace Pre\Phpx\Html\Example;

use function Pre\Phpx\classMatching;
use function Pre\Phpx\functionMatching;
use function Pre\Phpx\Html\render as renderHtml;

define("Namespaces", [
    "global",
    "Pre\\Phpx\\Html\\Example",
]);

function render($name, $props = null)
{
    if ($function = functionMatching(Namespaces, $name)) {
        return call_user_func($function, $props);
    }

    if ($class = classMatching(Namespaces, $name)) {
        return (new $class($props))->render();
    }

    return renderHtml($name, $props);
}
