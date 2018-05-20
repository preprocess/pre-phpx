<?php

namespace Pre\Phpx;

use Exception;

class Parser
{
    public function tokens($code)
    {
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

        $expressionLevel = 0;
        $expressionStarted = null;
        $expressionEnded = null;

        $nestedLevel = 0;
        $inQuote = false;

        $carry = 0;

        while ($cursor < $length) {
            if ($code[$cursor] === '"' || $code[$cursor] === "'" && $code[$cursor - 1] !== "\\") {
                $inQuote = !$inQuote;
            }

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

            if ($attributeStarted !== null && $attributeEnded !== null) {
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

            if ($code[$cursor] === "{" && $elementStarted === null && $nestedLevel > 0) {
                if ($expressionLevel === 0) {
                    $expressionStarted = $cursor;
                }

                $expressionLevel++;
            }

            if ($code[$cursor] === "}" && $elementStarted === null && $nestedLevel > 0) {
                $expressionLevel--;

                if ($expressionLevel === 0) {
                    $expressionEnded = $cursor;
                }
            }

            if ($expressionStarted !== null && $expressionEnded !== null) {
                $distance = $expressionEnded - $expressionStarted;

                $carry += $cursor;

                $before = trim(substr($code, 0, $expressionStarted));
                $expression = trim(substr($code, $expressionStarted + 1, $distance - 1));
                $after = trim(substr($code, $expressionEnded + 1));

                $tokens[] = $before;
                $tokens[] = ["expression" => trim($this->ast($this->nodes($this->tokens($expression)))), "started" => $carry];

                $code = $after;
                $length = strlen($code);
                $cursor = 0;

                $expressionStarted = null;
                $expressionEnded = null;

                continue;
            }

            preg_match("#^</?[a-zA-Z]#", substr($code, $cursor, 3), $matchesStart);

            if (
                count($matchesStart)
                && $attributeLevel < 1
                && $expressionLevel < 1
                && !$inQuote
            ) {
                $elementLevel++;

                if ($matchesStart[0][1] !== "/") {
                    $nestedLevel++;
                } else {
                    $nestedLevel--;
                }

                $elementStarted = $cursor;
            }

            preg_match("#^=>#", substr($code, $cursor - 1, 2), $matchesEqualBefore);
            preg_match("#^>=#", substr($code, $cursor, 2), $matchesEqualAfter);

            if (
                $code[$cursor] === ">"
                && !$matchesEqualBefore && !$matchesEqualAfter
                && $attributeLevel < 1
                && $elementStarted !== null
                && $expressionStarted === null
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
                    $attributes[$key] = $this->tokens($value);
                }

                if (count($attributes)) {
                    $token["attributes"] = $attributes;
                }

                // TODO
                // this was here after the last refactor
                // leaving it until this refactor is done
                //
                // if ($expressionStarted !== null) {
                //     $tokens[] = ["expression" => $before, "started" => $expressionStarted];
                // } else {
                    $tokens[] = $before;
                // }

                $tokens[] = $token;

                if (preg_match("#/>$#", $tag)) {
                    preg_match("#<([a-zA-Z]+)#", $tag, $matchesName);

                    // TODO
                    // might have to remove then when we test nodes
                    // added to make the tokens output cleaner
                    //
                    $previous = $tokens[count($tokens) - 1];
                    $tokens[count($tokens) - 1]["tag"] = trim(substr($previous["tag"], 0, strlen($previous["tag"]) - 2)) . ">";

                    $name = $matchesName[1];
                    $tokens[] = ["tag" => "</{$name}>"];
                }

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

        return array_values(array_filter($tokens));
    }

    public function nodes($tokens)
    {
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
                        $current["attributes"][$key] = $this->nodes($value);
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

                        // TODO
                        // assumption: attributes can only be literal code or nested tags
                        // figure out if this is true
                    }, $current["attributes"]);
                }
            }

            else if (isset($token["tag"]) && $token["tag"][1] === "/") {
                preg_match("#^</([a-zA-Z]+)#", $token["tag"], $matches);

                if ($current === null) {
                    throw new Exception("opening tag not found");
                }

                if ($matches[1] !== $current["name"]) {
                    throw new Exception("closing tag does not match opening tag");
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

        return $this->removeParents($nodes);
    }

    private function removeParents($nodes)
    {
        foreach ($nodes as $i => $_) {
            unset($nodes[$i]["parent"]);

            if (isset($nodes[$i]["children"])) {
                $nodes[$i]["children"] = $this->removeParents($nodes[$i]["children"]);
            }
        }

        return $nodes;
    }

    public function ast($nodes)
    {
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
                            $attributes["attr_{$key}"] = $this->parse([$value]);
                        }
                        else {
                            $attributes["attr_{$key}"] = $value["text"];
                        }
                    }
                }

                preg_match_all("#([a-zA-Z][a-zA-Z-_]+)={([^}]+)}#", $node["tag"], $dynamic);

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
                        $children[] = $this->parse([$child]);
                    }

                    else if (isset($child["expression"])) {
                        $children[] = $child["expression"];
                    }

                    else {
                        $children[] = "\"" . addslashes($child["text"]) . "\"";
                    }
                }

                $props["children"] = $children;

                if (function_exists("\\Pre\\Phpx\\Renderer\\__" . $node["name"])) {
                    $code .= "\\Pre\\Phpx\\Renderer\\__" . $node["name"] . "([" . PHP_EOL;
                }

                else {
                    $name = $node["name"];
                    $code .= "(new {$name})->render([" . PHP_EOL;
                }

                foreach ($props as $key => $value) {
                    if ($key === "children") {
                        $children = array_filter($children, function($child) {
                            return trim($child, "\"'");
                        });

                        $children = array_map("trim", $children);

                        $children = array_values($children);

                        if (count($children) === 1) {
                            $code .= "\"children\" => " . $children[0] . "," . PHP_EOL;
                        }

                        else {
                            $code .= "\"children\" => [" . PHP_EOL;

                            foreach ($children as $child) {
                                $code .= "{$child}," . PHP_EOL;
                            }

                            $code .= "]," . PHP_EOL;
                        }

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

    public static function compile($code)
    {
        static $parser;

        if (!$parser) {
            $parser = new static();
        }

        return $parser->ast($parser->nodes($parser->tokens($code)));
    }
}
