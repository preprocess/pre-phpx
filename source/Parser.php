<?php

namespace Pre\Phpx;

use Exception;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

class Parser
{
    private $printer;

    public function __construct($printer = null)
    {
        if (is_null($printer)) {
            $this->printer = new Printer();
        }
    }

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
                    $code,
                    $attributeStarted + 1,
                    $attributeEnded - $attributeStarted - 1
                );

                $attributes[$position] = $attribute;

                $before = substr($code, 0, $attributeStarted + 1);
                $after = substr($code, $attributeEnded);

                $code = $before . $position . $after;

                $cursor = $attributeStarted + $positionLength + 2 /* braces */;
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

                $tokens[] = ["type" => "literal", "value" => $before, "started" => $expressionStarted];
                $tokens[] = ["type" => "expression", "value" => $this->nodes($this->tokens($expression)), "started" => $carry];

                $code = $after;
                $length = strlen($code);
                $cursor = 0;

                $expressionStarted = null;
                $expressionEnded = null;

                continue;
            }

            preg_match("#^</?[a-zA-Z.]#", substr($code, $cursor, 3), $matchesStart);

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

                $token = ["type" => "tag", "value" => $tag, "started" => $carry];

                foreach ($attributes as $key => $value) {
                    $attributes[$key] = $this->tokens($value);
                }

                if (count($attributes)) {
                    $token["attributes"] = $attributes;
                }

                $tokens[] = ["type" => "literal", "value" => $before];
                $tokens[] = $token;

                if (preg_match("#/>$#", $tag)) {
                    preg_match("#<([a-zA-Z.\-_]+)#", $tag, $matchesName);

                    $previous = $tokens[count($tokens) - 1];
                    $tokens[count($tokens) - 1]["value"] = trim(substr($previous["value"], 0, strlen($previous["value"]) - 2)) . ">";

                    $name = $matchesName[1];
                    $tokens[] = ["type" => "tag", "value" => "</{$name}>"];
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

        $tokens[] = ["type" => "literal", "value" => trim($code)];

        return array_values(array_filter($tokens, function ($token) {
            if ($token["type"] === "literal" && !trim($token["value"])) {
                return false;
            }

            return true;
        }));
    }

    public function nodes($tokens)
    {
        $nodes = [];
        $current = null;

        $cursor = 0;
        $length = count($tokens);

        while ($cursor < $length) {
            $token =& $tokens[$cursor];

            if ($token["type"] === "tag" && $token["value"][1] !== "/") {
                preg_match("#^<([a-zA-Z.\-_]+)#", $token["value"], $matches);

                if ($current !== null) {
                    $token["parent"] =& $current;
                    $current["children"][] =& $token;
                } else {
                    $token["parent"] = null;
                    $nodes[] =& $token;
                }

                $current =& $token;
                $current["name"] = str_replace(".", "\\", $matches[1]);
                $current["children"] = [];

                if (isset($current["attributes"])) {
                    foreach ($current["attributes"] as $key => $value) {
                        $current["attributes"][$key] = $this->nodes($value);
                    }
                }
            } elseif ($token["type"] === "tag" && $token["value"][1] === "/") {
                preg_match("#^</([a-zA-Z.\-_]+)#", $token["value"], $matches);

                $name = str_replace(".", "\\", $matches[1]);

                if ($current === null) {
                    throw new Exception("opening tag not found");
                }

                if ($name !== $current["name"]) {
                    throw new Exception("closing tag does not match opening tag");
                }

                if ($current !== null) {
                    $current =& $current["parent"];
                }
            } elseif ($current !== null) {
                $token["parent"] =& $current;
                $current["children"][] =& $token;
            } else {
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

    public function translate($nodes, $quoteLiterals = false, $combineChildren = false)
    {
        $code = "";

        foreach ($nodes as $node) {
            if ($node["type"] === "literal") {
                if ($quoteLiterals) {
                    $code .= "\"" . addSlashes($node["value"]) . "\"";
                } else {
                    $code .= $node["value"];
                }

                if ($combineChildren) {
                    $code .= ", ";
                }

                continue;
            }
            if ($node["type"] === "expression") {
                $code .= $this->translate($node["value"]);

                if ($combineChildren) {
                    $code .= ", ";
                }
                
                continue;
            }

            $code .= " render(\"{$node["name"]}\", [" . PHP_EOL;
            

            if (isset($node["attributes"])) {
                preg_match_all("#(\S+)={[^}]+?}#", $node["value"], $matches);

                foreach ($matches[1] as $i => $name) {
                    $code .= "\"{$name}\" => " . $this->translate($node["attributes"][$i], false, false) . "," . PHP_EOL;
                }
            }

            if (isset($node["children"]) && count($node["children"]) > 0) {
                $translated = $this->translate($node["children"], true, true);

                if (count($node["children"]) > 1) {
                    $code .= "\"children\" => [{$translated}]," . PHP_EOL;
                } else {
                    $code .= "\"children\" => {$translated}," . PHP_EOL;
                }
            }

            $code .= "]) ";

            if ($combineChildren) {
                $code .= ", ";
            }
        }

        return trim($code, " .,");
    }

    public function format($code)
    {
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

        try {
            $parsed = $parser->parse($code);
        } catch (Exception $e) {
            // can't format, but we can still return...
            return $code;
        }

        return $this->printer->prettyPrintFile($parsed);
    }
}
