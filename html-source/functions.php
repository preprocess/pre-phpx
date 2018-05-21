<?php

namespace Pre\Phpx\Html;

use Exception;

define("ALLOWED_TAGS", [
    "a",
    "div",
    "input",
    "label",
    "form",
    "span",
]);

define("SELF_CLOSING_TAGS", [
    "input",
]);

define("RENAMED_PROPS", [
    "htmlFor" => "for",
]);

function render($name, $props = null)
{
    if (!in_array($name, ALLOWED_TAGS)) {
        throw new Exception("{$name} is not an allowed tag");
    }

    $props = propsFrom($props);

    $className = classNameFrom($props->className);
    unset($props->className);

    $style = styleFrom($props->style);
    unset($props->style);

    $children = childrenFrom($props->children);
    unset($props->children);

    $open = join(" ", array_filter([
        $name,
        $className,
        $style,
        attributesFrom($props),
    ]));

    if (in_array($name, SELF_CLOSING_TAGS)) {
        return "<{$open} />";
    }

    return "<{$open}>{$children}</{$name}>";
}

function propsFrom($props = null)
{
    if (is_null($props)) {
        $props = (object) [];
    }

    if (is_array($props)) {
        $props = (object) $props;
    }

    if (!isset($props->children)) {
        $props->children = null;
    }

    if (!isset($props->className)) {
        $props->className = null;
    }

    if (!isset($props->style)) {
        $props->style = null;
    }

    return $props;
}

function classNameFrom($className = null, $wrapped = true)
{
    if (is_null($className)) {
        return null;
    }

    if (is_callable($className)) {
        $result = classNameFrom($className(), false);
    }
    
    if (is_string($className)) {
        $result = $className;
    }

    if (is_array($className)) {
        $result = "";

        foreach ($className as $value) {
            $result .= classNameFrom($value, false) . " ";
        }
        
        $result = trim($result);
    }

    if ($wrapped) {
        return "class=\"{$result}\"";
    }
    
    return $result;
}

function styleFrom($style = null, $wrapped = true)
{
    if (is_null($style)) {
        return null;
    }

    if (is_object($style)) {
        $style = (array) $style;
    }

    if (!is_array($style)) {
        throw new Exception("style must be an array or an object");
    }

    $result = "";

    foreach ($style as $key => $value) {
        if (is_callable($value)) {
            $value = $value();
        }

        $result .= "{$key}: {$value}; ";
    }
    
    $result = trim($result);

    if ($wrapped) {
        return "style=\"{$result}\"";
    }
    
    return $result;
}

function childrenFrom($children = null)
{
    if (is_array($children)) {
        return join("", $children);
    }

    return $children;
}

function attributesFrom($props)
{
    $attributes = [];

    foreach ($props as $key => $value) {
        if (in_array($key, array_keys(RENAMED_PROPS))) {
            $key = RENAMED_PROPS[$key];
        }
        
        if (is_callable($value)) {
            $value = $value();
        }

        $attributes[] = "{$key}=\"{$value}\"";
    }

    return join(" ", $attributes);
}
