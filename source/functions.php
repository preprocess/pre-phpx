<?php

namespace Pre\Phpx;

function compile($code, $printer = null)
{
    // ...we should memoize this
    $parser = new Parser($printer);

    return $parser->format($parser->translate($parser->nodes($parser->tokens($code))));
}

function functionMatching(array $namespaces, $name)
{
    if (in_array("global", $namespaces)) {
        if ($matching = globalFunctionMatching($name)) {
            return $matching;
        }
    }

    foreach ($namespaces as $namespace) {
        if (function_exists("\\{$namespace}\\{$name}")) {
            return "\\{$namespace}\\{$name}";
        }
    }
}

function globalFunctionMatching($name)
{
    if (function_exists("\\{$name}")) {
        return "\\{$name}";
    }
}

function classMatching(array $namespaces, $name)
{
    if (in_array("global", $namespaces)) {
        if ($matching = globalClassMatching($name)) {
            return $matching;
        }
    }

    foreach ($namespaces as $namespace) {
        if (class_exists("\\{$namespace}\\{$name}")) {
            return "\\{$namespace}\\{$name}";
        }
    }
}

function globalClassMatching($name)
{
    if (class_exists("\\{$name}")) {
        return "\\{$name}";
    }
}
