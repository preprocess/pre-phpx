<?php

namespace Pre\Phpx\Html;

use Exception;

class Renderer
{
    private $allowedTags = [
        "a",
        "div",
        "input",
        "label",
        "form",
        "span",
    ];

    private $selfClosingTags = [
        "input",
    ];

    private $renamedProps = [
        "htmlFor" => "for",
    ];

    public function render($name, $props = null)
    {
        if (!in_array($name, $this->allowedTags)) {
            throw new Exception("{$name} is not an allowed tag");
        }

        $props = $this->propsFrom($props);

        $className = $this->classNameFrom($props->className);
        unset($props->className);

        $style = $this->styleFrom($props->style);
        unset($props->style);

        $children = $this->childrenFrom($props->children);
        unset($props->children);

        $open = join(" ", array_filter([
            $name,
            $className,
            $style,
            $this->attributesFrom($props),
        ]));

        if (in_array($name, $this->selfClosingTags)) {
            return "<{$open} />";
        }

        return "<{$open}>{$children}</{$name}>";
    }

    private function propsFrom($props = null)
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

    private function classNameFrom($className = null, $wrapped = true)
    {
        if (is_null($className)) {
            return null;
        }

        if (is_callable($className)) {
            $result = $this->classNameFrom($className(), false);
        }
    
        if (is_string($className)) {
            $result = $className;
        }

        if (is_array($className)) {
            $result = "";

            foreach ($className as $value) {
                $result .= $this->classNameFrom($value, false) . " ";
            }
        
            $result = trim($result);
        }

        if ($wrapped) {
            return "class=\"{$result}\"";
        }
    
        return $result;
    }

    private function styleFrom($style = null, $wrapped = true)
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

    private function childrenFrom($children = null)
    {
        if (is_array($children)) {
            return join("", $children);
        }

        return $children;
    }

    private function attributesFrom($props)
    {
        $attributes = [];

        foreach ($props as $key => $value) {
            if (in_array($key, array_keys($this->renamedProps))) {
                $key = $this->renamedProps[$key];
            }
        
            if (is_callable($value)) {
                $value = $value();
            }

            $attributes[] = "{$key}=\"{$value}\"";
        }

        return join(" ", $attributes);
    }
}
