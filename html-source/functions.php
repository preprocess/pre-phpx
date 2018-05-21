<?php

namespace Pre\Phpx\Html;

function renderer()
{
    static $renderer;

    if (!$renderer) {
        $renderer = new Renderer();
    }

    return $renderer;
}

function render($name, $props = null)
{
    return renderer()->render($name, $props);
}

function propsFrom($props = null)
{
    return renderer()->propsFrom($props);
}

function format($markup)
{
    return renderer()->format($markup);
}
