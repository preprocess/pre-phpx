<?php

namespace Pre\Phpx\Html;

function render($name, $props = null)
{
    static $renderer;

    if (!$renderer) {
        $renderer = new Renderer();
    }

    return $renderer->render($name, $props);
}
