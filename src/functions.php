<?php

namespace Pre\Phpx;

use Exception;

function tokens($code) {
    $tokens = [];

    $length = strlen($code);
    $cursor = 0;

    $elementLevel = 0;
    $elementStarted = null;
    $elementEnded = null;

    $attributes = [];
    $attributeLevel = 0;
    $attributeStarted = null;
    $attributeEnded = null;

    $carry = 0;

    while ($cursor < $length) {
        if ($code[$cursor] === "{" && $elementStarted !== null) {
            if ($attributeLevel === 0) {
                $attributeStarted = $cursor;
            }

            $attributeLevel++;
        }

        if ($code[$cursor] === "}" && $elementStarted !== null) {
            $attributeLevel--;

            if ($attributeLevel === 0) {
                $attributeEnded = $cursor;
            }
        }

        if ($attributeStarted && $attributeEnded) {
            $position = (string) count($attributes);
            $positionLength = strlen($position);

            $attribute = substr(
                $code, $attributeStarted + 1, $attributeEnded - $attributeStarted - 1
            );

            $attributes[$position] = $attribute;

            $before = substr($code, 0, $attributeStarted + 1);
            $after = substr($code, $attributeEnded);

            $code = $before . $position . $after;

            $cursor = $attributeStarted + $positionLength + 2 /* curlies */;
            $length = strlen($code);

            $attributeStarted = null;
            $attributeEnded = null;

            continue;
        }

        preg_match("#^</?[a-zA-Z]#", substr($code, $cursor, 3), $matchesStart);

        if (count($matchesStart) && $attributeLevel < 1) {
            $elementLevel++;
            $elementStarted = $cursor;
        }

        preg_match("#^=>#", substr($code, $cursor - 1, 2), $matchesEqualBefore);
        preg_match("#^>=#", substr($code, $cursor, 2), $matchesEqualAfter);

        if (
            $code[$cursor] === ">"
            && !$matchesEqualBefore && !$matchesEqualAfter
            && $attributeLevel < 1
            && $elementStarted !== null
        ) {
            $elementLevel--;
            $elementEnded = $cursor;
        }

        if ($elementStarted !== null && $elementEnded !== null) {
            $distance = $elementEnded - $elementStarted;

            $carry += $cursor;

            $before = trim(substr($code, 0, $elementStarted));
            $tag = trim(substr($code, $elementStarted, $distance + 1));
            $after = trim(substr($code, $elementEnded + 1));

            $token = ["tag" => $tag, "started" => $carry];

            foreach ($attributes as $key => $value) {
                $attributes[$key] = tokens($value);
            }

            if (count($attributes)) {
                $token["attributes"] = $attributes;
            }

            $tokens[] = $before;
            $tokens[] = $token;

            $attributes = [];

            $code = $after;
            $length = strlen($code);
            $cursor = 0;

            $elementStarted = null;
            $elementEnded = null;

            continue;
        }

        $cursor++;
    }

    $tokens[] = trim($code);

    return $tokens;
}

function nodes($tokens) {
    $nodes = [];
    $current = null;

    $cursor = 0;
    $length = count($tokens);

    while ($cursor < $length) {
        $token =& $tokens[$cursor];

        if (!is_array($token)) {
            $token = ["text" => $token];
        }

        if (isset($token["tag"]) && $token["tag"][1] !== "/") {
            preg_match("#^<([a-zA-Z]+)#", $token["tag"], $matches);

            if ($current !== null) {
                $token["parent"] =& $current;
                $current["children"][] =& $token;
            } else {
                $token["parent"] = null;
                $nodes[] =& $token;
            }

            $current =& $token;
            $current["name"] = $matches[1];
            $current["children"] = [];

            if (isset($current["attributes"])) {
                foreach ($current["attributes"] as $key => $value) {
                    $current["attributes"][$key] = nodes($value);
                }

                $current["attributes"] = array_map(function($item) {

                    foreach ($item as $value) {
                        if (isset($value["tag"])) {
                            return $value;
                        }
                    }

                    foreach ($item as $value) {
                        if (!empty($value["text"])) {
                            return $value;
                        }
                    }

                    return null;

                }, $current["attributes"]);
            }
        }

        else if (isset($token["tag"]) && $token["tag"][1] === "/") {
            preg_match("#^</([a-zA-Z]+)#", $token["tag"], $matches);

            if ($current === null) {
                throw new Exception("no open tag");
            }

            if ($matches[1] !== $current["name"]) {
                throw new Exception("no matching open tag");
            }

            if ($current !== null) {
                $current =& $current["parent"];
            }
        }

        else if ($current !== null) {
            $token["parent"] =& $current;
            $current["children"][] =& $token;
        }

        else {
            $nodes[] =& $token;
        }

        $cursor++;
    }

    return $nodes;
}

function parse($nodes) {
    $code = "";

    foreach ($nodes as $node) {
        if (isset($node["text"])) {
            $code .= $node["text"] . PHP_EOL;
        }

        if (isset($node["tag"])) {
            $props = [];
            $attributes = [];

            if (isset($node["attributes"])) {
                $node["attributes"] = array_filter($node["attributes"]);

                foreach ($node["attributes"] as $key => $value) {
                    if (isset($value["tag"])) {
                        $attributes["attr_{$key}"] = parse([$value]);
                    }
                    else {
                        $attributes["attr_{$key}"] = $value["text"];
                    }
                }
            }

            preg_match_all("#([a-zA-Z]+)={([^}]+)}#", $node["tag"], $dynamic);

            if (count($dynamic[0])) {
                foreach($dynamic[1] as $key => $value) {
                    if (!isset($attributes["attr_{$key}"])) {
                        throw new Exception("attribute not defined");
                    }

                    $props["{$value}"] = $attributes["attr_{$key}"];
                }
            }

            $children = [];

            foreach ($node["children"] as $child) {
                if (isset($child["tag"])) {
                    $children[] = parse([$child]);
                }

                else {
                    $children[] = "\"" . addslashes($child["text"]) . "\"";
                }
            }

            $props["children"] = $children;

            if (function_exists("Pre\\Phpx\\Renderer\\_" . $node["name"])) {
                $code .= "Pre\\Phpx\\Renderer\\_" . $node["name"] . "([" . PHP_EOL;
            }

            else {
                $code .= $node["name"] . "([" . PHP_EOL;
            }

            foreach ($props as $key => $value) {
                if ($key === "children") {
                    $code .= "\"children\" => [" . PHP_EOL;

                    foreach ($children as $child) {
                        $code .= "{$child}," . PHP_EOL;
                    }

                    $code .= "]," . PHP_EOL;
                }

                else {
                    $code .= "\"{$key}\" => {$value}," . PHP_EOL;
                }
            }

            $code .= "])";
        }
    }

    return $code;
}

function compile($code) {
    return parse(nodes(tokens($code)));
}

namespace Pre\Phpx\Renderer;

use Closure;
use Pre\Collections\Collection;

function element($name, $props, callable $attrs = null) {
    $code = "<{$name}";

    $className = $props["className"] ?? null;

    if (is_callable($className)) {
        $className = $className();
    }

    if ($className instanceof Collection) {
        $className = $className->toArray();
    }

    if (is_array($className)) {
        $combined = "";

        foreach ($className as $key => $value) {
            if (is_string($key)) {
                $combined .= !!$value ? " {$key}" : "";
            }

            else {
                $combined .= " {$value}";
            }
        }

        $className = trim($combined);
    }

    if ($className) {
        $code .= " class='{$className}'";
    }

    $style = $props["style"] ?? null;

    if (is_callable($style)) {
        $style = $style();
    }

    if ($style instanceof Collection) {
        $style = $style->toArray();
    }

    if (is_array($style)) {
        $styles = [];

        foreach ($style as $key => $value) {
            $styles[] = "{$key}: {$value}";
        }

        $style = join("; ", $styles);
    }

    if ($style) {
        $code .= " style='{$style}'";
    }

    $code .= ">";

    foreach ($props["children"] as $child) {
        $code .= $child;
    }

    $code .= "</{$name}>";

    return $code;
}


define("ELEMENTS", [
    "a",
    "abbr",
    "address",
    "area",
    "article",
    "aside",
    "audio",
    "b",
    "base",
    "bdi",
    "bdo",
    "blockquote",
    "body",
    "br",
    "button",
    "canvas",
    "caption",
    "cite",
    "code",
    "col",
    "colgroup",
    "data",
    "datalist",
    "dd",
    "del",
    "details",
    "dfn",
    "div",
    "dl",
    "dt",
    "em",
    "embed",
    "fieldset",
    "figcaption",
    "figure",
    "footer",
    "form",
    "h1",
    "h2",
    "h3",
    "h4",
    "h5",
    "h6",
    "head",
    "header",
    "hr",
    "html",
    "i",
    "iframe",
    "img",
    "input",
    "ins",
    "kbd",
    "label",
    "legend",
    "li",
    "link",
    "main",
    "map",
    "mark",
    "meta",
    "meter",
    "nav",
    "noframes",
    "noscript",
    "object",
    "ol",
    "optgroup",
    "option",
    "output",
    "p",
    "param",
    "pre",
    "progress",
    "q",
    "rp",
    "rt",
    "rtc",
    "ruby",
    "s",
    "samp",
    "script",
    "section",
    "select",
    "slot",
    "small",
    "source",
    "span",
    "strong",
    "style",
    "sub",
    "summary",
    "sup",
    "table",
    "tbody",
    "td",
    "template",
    "textarea",
    "tfoot",
    "th",
    "thead",
    "time",
    "title",
    "tr",
    "track",
    "u",
    "ul",
    "var",
    "video",
    "wbr",
]);

foreach (ELEMENTS as $element) {
    eval("namespace Pre\Phpx\Renderer { function _{$element}(\$props) {
        return element('{$element}', \$props);
    } }");
}
