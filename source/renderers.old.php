<?php

namespace Pre\Phpx\Renderer;

use Pre\Collections\Collection;

function memoize(callable $function) {
    return function(...$arguments) use ($function) {
        static $cache = [];

        $key = md5(serialize($arguments));

        if (!isset($cache[$key])) {
            $cache[$key] = call_user_func_array($function, $arguments);
        }

        return $cache[$key];
    };
}

$GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"] = memoize("\\Pre\\Phpx\\Renderer\\element");
$GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"] = memoize("\\Pre\\Phpx\\Renderer\\attribute");
$GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"] = memoize("\\Pre\\Phpx\\Renderer\\__attributes");

function element($name, $props, callable $modify = null, $isSelfClosing = false) {
    $code = "<{$name}";

    if ($className = className($props)) {
        $code .= " class='{$className}'";
    }

    if ($style = style($props)) {
        $code .= " style='{$style}'";
    }

    if (is_callable($modify)) {
        $code = $modify($code, $props);
    }

    if ($isSelfClosing) {
        $code .= "/";
    }

    $code .= ">";
    $code .= children($props);

    if (!$isSelfClosing) {
        $code .= "</{$name}>";
    }

    return $code;
}

function attribute($code, $props, $key, $alias = null) {
    $value = $props[$key] ?? null;

    $use = $alias ?? $key;

    if ($value) {
        $code .= " {$use}=\"{$value}\"";
    }

    return $code;
}

function className($props) {
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

    return $className;
}

function style($props) {
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

    return $style;
}

function children($props) {
    $children = [];

    if ($props["children"] instanceof Collection) {
        $props["children"] = $props["children"]->toArray();
    }

    if (!is_array($props["children"])) {
        $props["children"] = [$props["children"]];
    }

    foreach ($props["children"] as $child) {
        $children[] = $child;
    }

    return trim(join(" ", $children));
}

function __attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    foreach ($props as $key => $value) {
        $prefix = strtolower(substr($key, 0, 5));

        if ($prefix === "data-" || $prefix === "aria-") {
            $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, $key);
        }
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onAbort");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onAutoComplete");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onAutoCompleteError");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onBlur");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onCancel");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onCanPlay");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onCanPlayThrough");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onChange");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onClick");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onClose");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onContextMenu");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onCueChange");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDblClick");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDrag");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDragEnd");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDragEnter");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDragExit");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDragLeave");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDragOver");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDragStart");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDrop");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onDurationChange");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onEmptied");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onEnded");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onError");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onFocus");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onInput");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onInvalid");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onKeyDown");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onKeyPress");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onKeyUp");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onLoad");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onLoadedData");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onLoadedMetaData");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onLoadStart");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onMouseDown");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onMouseEnter");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onMouseLeave");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onMouseMove");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onMouseOut");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onMouseOver");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onMouseUp");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onMouseWheel");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onPause");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onPlay");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onPlaying");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onProgress");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onRateChange");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onReset");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onResize");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onScroll");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onSeeked");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onSeeking");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onSelect");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onShow");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onSort");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onStalled");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onSubmit");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onSuspend");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onTimeUpdate");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onToggle");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onVolumeChange");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onWaiting");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "accessKey");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "contentEditable");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "contextMenu");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "dir");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "hidden");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "id");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "lang");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "tabIndex");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "title");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "translate");
    return $code;
}

function __a($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("a", $props, "\\Pre\\Phpx\\Renderer\\__a__attributes");
}

function __a__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "download");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "href");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "hrefLang");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "rel");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "target");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");

    return $code;
}

function __abbr($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("abbr", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __address($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("address", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __area($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("area", $props, "\\Pre\\Phpx\\Renderer\\__area__attributes");
}

function __area__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "alt");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "coords");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "download");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "href");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "hrefLang");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "media");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "rel");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "shape");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "target");

    return $code;
}

function __article($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("article", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __aside($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("aside", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __audio($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("audio", $props, "\\Pre\\Phpx\\Renderer\\__audio__attributes");
}

function __audio__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoPlay");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoBuffer");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "buffered");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "controls");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "loop");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "muted");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "played");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "preload");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "src");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "volume");

    return $code;
}

function __b($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("b", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __base($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("base", $props, "\\Pre\\Phpx\\Renderer\\__base__attributes");
}

function __base__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "href");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "target");

    return $code;
}

function __bdi($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("bdi", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __bdo($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("bdo", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __blockquote($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("blockquote", $props, "\\Pre\\Phpx\\Renderer\\__blockquote__attributes");
}

function __blockquote__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "cite");

    return $code;
}

function __body($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("body", $props, "\\Pre\\Phpx\\Renderer\\__body__attributes");
}

function __body__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onAfterPrint");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onBeforePrint");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onBeforeUnload");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onHashChange");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onMessage");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onOffline");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onOnline");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onPopState");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onRedo");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onResize");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onStorage");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onUndo");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "onUnload");

    return $code;
}

function __br($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("br", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __button($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("button", $props, "\\Pre\\Phpx\\Renderer\\__button__attributes");
}

function __button__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoFocus");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "disabled");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "form");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formAction");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formEncType");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formMethod");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formNoValidate");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formTarget");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "value");

    return $code;
}

function __canvas($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("canvas", $props, "\\Pre\\Phpx\\Renderer\\__canvas__attributes");
}

function __canvas__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "height");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "width");

    return $code;
}

function __caption($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("caption", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __cite($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("cite", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __code($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("code", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __col($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("col", $props, "\\Pre\\Phpx\\Renderer\\__col__attributes");
}

function __col__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "span");

    return $code;
}

function __colgroup($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("colgroup", $props, "\\Pre\\Phpx\\Renderer\\__colgroup__attributes");
}

function __colgroup__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "span");

    return $code;
}

function __data($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("data", $props, "\\Pre\\Phpx\\Renderer\\__data__attributes");
}

function __data__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "value");

    return $code;
}

function __datalist($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("datalist", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __dd($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("dd", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __del($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("del", $props, "\\Pre\\Phpx\\Renderer\\__del__attributes");
}

function __del__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "cite");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "dateTime");

    return $code;
}

function __details($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("details", $props, "\\Pre\\Phpx\\Renderer\\__details__attributes");
}

function __details__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "open");

    return $code;
}

function __dfn($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("dfn", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __div($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("div", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __dl($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("dl", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __dt($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("dt", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __em($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("em", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __embed($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("embed", $props, "\\Pre\\Phpx\\Renderer\\__embed__attributes");
}

function __embed__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "height");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "src");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "width");

    return $code;
}

function __fieldset($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("fieldset", $props, "\\Pre\\Phpx\\Renderer\\__fieldset__attributes");
}

function __fieldset__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "disabled");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "form");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");

    return $code;
}

function __figcaption($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("figcaption", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __figure($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("figure", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __footer($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("footer", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __form($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("form", $props, "\\Pre\\Phpx\\Renderer\\__form__attributes");
}

function __form__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "acceptCharset", "accept-charset");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "action");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoComplete");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "encType");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "method");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "noValidate");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "target");

    return $code;
}

function __h1($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("h1", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __h2($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("h2", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __h3($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("h3", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __h4($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("h4", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __h5($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("h5", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __h6($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("h6", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __head($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("head", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __header($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("header", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __hr($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("hr", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __html($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("html", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __i($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("i", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __iframe($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("iframe", $props, "\\Pre\\Phpx\\Renderer\\__iframe__attributes");
}

function __iframe__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "allowFullscreen");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "allowPaymentRequest");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "frameBorder");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "height");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "longDesc");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "marginHeight");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "marginWidth");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "scrolling");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "sandbox");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "src");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "srcDoc");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "width");

    return $code;
}

function __img($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("img", $props, "\\Pre\\Phpx\\Renderer\\__img__attributes");
}

function __img__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "alt");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "crossOrigin");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "height");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "isMap");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "longDesc");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "sizes");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "src");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "srcSet");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "width");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "useMap");

    return $code;
}

function __input($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("input", $props, "\\Pre\\Phpx\\Renderer\\__input__attributes", true);
}

function __input__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "accept");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "accessKey");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoComplete");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoFocus");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "capture");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "checked");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "disabled");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "form");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formAction");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formEncType");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formMethod");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formNoValidate");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "formTarget");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "height");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "inputMode");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "list");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "max");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "maxLength");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "min");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "minLength");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "multiple");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "pattern");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "placeholder");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "readOnly");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "required");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "selectionDirection");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "selectionEnd");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "selectionStart");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "size");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "spellCheck");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "src");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "step");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "useMap");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "value");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "width");

    return $code;
}

function __ins($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("ins", $props, "\\Pre\\Phpx\\Renderer\\__ins__attributes");
}

function __ins__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "cite");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "dateTime");

    return $code;
}

function __kbd($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("kbd", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __label($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("label", $props, "\\Pre\\Phpx\\Renderer\\__label__attributes");
}

function __label__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "for");

    return $code;
}

function __legend($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("legend", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __li($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("li", $props, "\\Pre\\Phpx\\Renderer\\__li__attributes");
}

function __li__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "value");

    return $code;
}

function __link($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("link", $props, "\\Pre\\Phpx\\Renderer\\__link__attributes");
}

function __link__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "as");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "crossOrigin");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "href");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "hrefLang");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "media");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "rel");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "sizes");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");

    return $code;
}

function __main($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("main", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __map($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("map", $props, "\\Pre\\Phpx\\Renderer\\__map__attributes");
}

function __map__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");

    return $code;
}

function __mark($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("mark", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __meta($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("meta", $props, "\\Pre\\Phpx\\Renderer\\__meta__attributes");
}

function __meta__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "charset");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "content");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "httpEquiv", "http-equiv");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");

    return $code;
}

function __meter($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("meter", $props, "\\Pre\\Phpx\\Renderer\\__meter__attributes");
}

function __meter__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "value");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "min");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "max");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "low");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "high");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "optimum");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "form");

    return $code;
}

function __nav($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("nav", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __noframes($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("noframes", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __noscript($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("noscript", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __object($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("object", $props, "\\Pre\\Phpx\\Renderer\\__object__attributes");
}

function __object__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "archive");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "border");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "classId");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "codeBase");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "codeType");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "data");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "declare");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "form");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "height");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "standby");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "typeMustMatch");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "useMap");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "width");

    return $code;
}

function __ol($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("ol", $props, "\\Pre\\Phpx\\Renderer\\__ol__attributes");
}

function __ol__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "reversed");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "start");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");

    return $code;
}

function __optgroup($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("optgroup", $props, "\\Pre\\Phpx\\Renderer\\__optgroup__attributes");
}

function __optgroup__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "disabled");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "label");

    return $code;
}

function __option($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("option", $props, "\\Pre\\Phpx\\Renderer\\__option__attributes");
}

function __option__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "disabled");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "label");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "selected");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "value");

    return $code;
}

function __output($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("output", $props, "\\Pre\\Phpx\\Renderer\\__output__attributes");
}

function __output__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "for");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "form");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");

    return $code;
}

function __p($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("p", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __param($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("param", $props, "\\Pre\\Phpx\\Renderer\\__param__attributes");
}

function __param__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "value");

    return $code;
}

function __pre($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("pre", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __progress($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("progress", $props, "\\Pre\\Phpx\\Renderer\\__progress__attributes");
}

function __progress__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "max");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "value");

    return $code;
}

function __q($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("q", $props, "\\Pre\\Phpx\\Renderer\\__q__attributes");
}

function __q__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "cite");

    return $code;
}

function __rp($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("rp", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __rt($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("rt", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __rtc($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("rtc", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __ruby($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("ruby", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __s($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("s", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __samp($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("samp", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __script($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("script", $props, "\\Pre\\Phpx\\Renderer\\__script__attributes");
}

function __script__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "async");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "crossOrigin");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "defer");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "integrity");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "src");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "text");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");

    return $code;
}

function __section($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("section", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __select($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("select", $props, "\\Pre\\Phpx\\Renderer\\__select__attributes");
}

function __select__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoFocus");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "disabled");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "form");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "multiple");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "required");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "size");

    return $code;
}

function __slot($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("slot", $props, "\\Pre\\Phpx\\Renderer\\__slot__attributes");
}

function __slot__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");

    return $code;
}

function __small($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("small", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __source($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("source", $props, "\\Pre\\Phpx\\Renderer\\__source__attributes");
}

function __source__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "src");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");

    return $code;
}

function __span($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("span", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __strong($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("strong", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __style($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("style", $props, "\\Pre\\Phpx\\Renderer\\__style__attributes");
}

function __style__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "type");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "media");

    return $code;
}

function __sub($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("sub", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __summary($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("summary", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __sup($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("sup", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __table($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("table", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __tbody($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("tbody", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __td($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("td", $props, "\\Pre\\Phpx\\Renderer\\__td__attributes");
}

function __td__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "colSpan");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "headers");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "rowSpan");

    return $code;
}

function __template($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("template", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __textarea($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("textarea", $props, "\\Pre\\Phpx\\Renderer\\__textarea__attributes");
}

function __textarea__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoComplete");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoFocus");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "cols");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "disabled");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "form");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "maxLength");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "minLength");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "name");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "placeholder");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "readOnly");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "required");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "rows");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "selectionDirection");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "selectionEnd");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "selectionStart");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "spellCheck");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "wrap");

    return $code;
}

function __tfoot($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("tfoot", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __th($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("th", $props, "\\Pre\\Phpx\\Renderer\\__th__attributes");
}

function __th__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "colSpan");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "headers");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "rowSpan");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "scope");

    return $code;
}

function __thead($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("thead", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __time($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("time", $props, "\\Pre\\Phpx\\Renderer\\__time__attributes");
}

function __time__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "dateTime");

    return $code;
}

function __title($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("title", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __tr($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("tr", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __track($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("track", $props, "\\Pre\\Phpx\\Renderer\\__track__attributes");
}

function __track__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "default");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "kind");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "label");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "src");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "srcLang");

    return $code;
}

function __u($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("u", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __ul($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("ul", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __var($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("var", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}

function __video($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("video", $props, "\\Pre\\Phpx\\Renderer\\__video__attributes");
}

function __video__attributes($code, $props) {
    if (count($props) < 1) {
        return $code;
    }

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTES"]($code, $props);

    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "autoPlay");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "buffered");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "controls");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "crossOrigin");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "height");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "loop");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "muted");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "played");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "preload");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "poster");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "src");
    $code = $GLOBALS["PRE_PHPX_MEMOIZED_ATTRIBUTE"]($code, $props, "width");

    return $code;
}

function __wbr($props) {
    return $GLOBALS["PRE_PHPX_MEMOIZED_ELEMENT"]("wbr", $props, "\\Pre\\Phpx\\Renderer\\__attributes");
}
