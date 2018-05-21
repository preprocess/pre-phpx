<?php

namespace Pre\Phpx\Html\Example;

use function Pre\Phpx\classMatching;
use function Pre\Phpx\functionMatching;
use function Pre\Phpx\Html\render as renderHtml;
use function Pre\Phpx\Html\propsFrom;

define("NAMESPACES", [
    "global",
    "Pre\\Phpx\\Html\\Example",
]);

function render($name, $props = null)
{
    $props = propsFrom($props);
    
    if ($function = functionMatching(NAMESPACES, $name)) {
        return call_user_func($function, $props);
    }

    if ($class = classMatching(NAMESPACES, $name)) {
        return (new $class($props))->render();
    }

    return renderHtml($name, $props);
}
