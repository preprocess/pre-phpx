<?php

namespace Pre\Phpx;

function functionMatching(array $namespaces, $name)
{
    if (in_array("global", $namespaces)) {
        if (function_exists("\\{$name}")) {
            return "\\{$name}";
        }
    }

    foreach ($namespaces as $namespace) {
        if (function_exists("\\{$namespace}\\{$name}")) {
            return "\\{$namespace}\\{$name}";
        }
    }
}

function classMatching(array $namespaces, $name)
{
    if (in_array("global", $namespaces)) {
        if (class_exists("\\{$name}", false)) {
            return "\\{$name}";
        }
    }

    foreach ($namespaces as $namespace) {
        if (class_exists("\\{$namespace}\\{$name}", false)) {
            return "\\{$namespace}\\{$name}";
        }
    }
}
