<?php

# This file is generated, changes you make will be lost.
# Make your changes in /Users/assertchris/Source/preprocess/pre-phpx/examples/custom-renderer-fixture.pre instead.

// define a custom renderer
function render($name, $props = null)
{
    $props = (object) $props;

    if ($name === RequiredFieldError::class) {
        return RequiredFieldError($props);
    }

    if ($name === Fields::class) {
        return Fields($props);
    }

    if (!$props) {
        $props = (object) ["class" => "", "children" => null];
    }

    if (isset($props->children) && is_array($props->children)) {
        $props->children = join("", $props->children);
    }

    if (!isset($props->children)) {
        $props->children = null;
    }

    return "<{$name} class=\"{$props->class}\">{$props->children}</{$name}>";
}

function RequiredFieldError($props)
{
    return (render("div", [
"class" => "error",
"children" => [render("span", [
"class" => "required-error-icon",
]) , "You forgot the", " " . $props->name . " ", "field."],
]));
}

function Fields($props)
{
    return render("RequiredFieldError", [
"name" => "email",
]) ;
}
