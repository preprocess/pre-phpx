<?php

namespace Pre\Phpx\Html;

use Exception;
use Gajus\Dindent\Indenter;
use function Pre\Phpx\globalClassMatching;
use function Pre\Phpx\globalFunctionMatching;

class Renderer
{
    private $allowedTags = [
        // html
        "html",
        "link",
        "meta",
        "base",
        "script",
        "style",
        "title",
        "body",
        "address",
        "article",
        "aside",
        "footer",
        "header",
        "h1",
        "h2",
        "h3",
        "h4",
        "h5",
        "h6",
        "hgroup",
        "nav",
        "section",
        "blockquote",
        "cite",
        "dd",
        "dt",
        "dl",
        "dir",
        "div",
        "figcaption",
        "figure",
        "hr",
        "li",
        "ol",
        "ul",
        "menu",
        "main",
        "p",
        "pre",
        "a",
        "abbr",
        "b",
        "bdi",
        "bdo",
        "br",
        "code",
        "data",
        "time",
        "dfn",
        "em",
        "i",
        "kbd",
        "mark",
        "q",
        "rp",
        "ruby",
        "rt",
        "rtc",
        "rb",
        "s",
        "del",
        "ins",
        "samp",
        "small",
        "span",
        "strong",
        "sub",
        "sup",
        "tt",
        "u",
        "var",
        "wbr",
        "area",
        "map",
        "audio",
        "source",
        "img",
        "track",
        "video",
        "applet",
        "object",
        "embed",
        "iframe",
        "noembed",
        "param",
        "picture",
        "canvas",
        "noscript",
        "caption",
        "table",
        "col",
        "colgroup",
        "tbody",
        "tr",
        "td",
        "tfoot",
        "th",
        "thead",
        "button",
        "datalist",
        "option",
        "fieldset",
        "label",
        "form",
        "input",
        "legend",
        "meter",
        "optgroup",
        "select",
        "output",
        "progress",
        "textarea",
        "details",
        "dialog",
        "menuitem",
        "summary",
        "content",
        "element",
        "shadow",
        "slot",
        "template",
        "acronym",
        "basefont",
        "bgsound",
        "big",
        "blink",
        "center",
        "command",
        "font",
        "frame",
        "frameset",
        "image",
        "isindex",
        "keygen",
        "listing",
        "marquee",
        "multicol",
        "nextid",
        "nobr",
        "noframes",
        "plaintext",
        "spacer",
        "strike",
        "xmp",

        // svg
        "a",
        "altGlyph",
        "altGlyphDef",
        "altGlyphItem",
        "animate",
        "animateColor",
        "animateMotion",
        "animateTransform",
        "circle",
        "clipPath",
        "color-profile",
        "cursor",
        "defs",
        "desc",
        "discard",
        "ellipse",
        "feBlend",
        "feColorMatrix",
        "feComponentTransfer",
        "feComposite",
        "feConvolveMatrix",
        "feDiffuseLighting",
        "feDisplacementMap",
        "feDistantLight",
        "feDropShadow",
        "feFlood",
        "feFuncA",
        "feFuncB",
        "feFuncG",
        "feFuncR",
        "feGaussianBlur",
        "feImage",
        "feMerge",
        "feMergeNode",
        "feMorphology",
        "feOffset",
        "fePointLight",
        "feSpecularLighting",
        "feSpotLight",
        "feTile",
        "feTurbulence",
        "filter",
        "font",
        "font-face",
        "font-face-format",
        "font-face-name",
        "font-face-src",
        "font-face-uri",
        "foreignObject",
        "g",
        "glyph",
        "glyphRef",
        "hatch",
        "hatchpath",
        "hkern",
        "image",
        "line",
        "linearGradient",
        "marker",
        "mask",
        "mesh",
        "meshgradient",
        "meshpatch",
        "meshrow",
        "metadata",
        "missing-glyph",
        "mpath",
        "path",
        "pattern",
        "polygon",
        "polyline",
        "radialGradient",
        "rect",
        "script",
        "set",
        "solidcolor",
        "stop",
        "style",
        "svg",
        "switch",
        "symbol",
        "text",
        "textPath",
        "title",
        "tref",
        "tspan",
        "unknown",
        "use",
        "view",
        "vkern",
    ];

    private $selfClosingTags = [
        "area",
        "base",
        "br",
        "col",
        "embed",
        "hr",
        "img",
        "input",
        "keygen",
        "link",
        "meta",
        "param",
        "source",
        "track",
        "wbr",
    ];

    private $renamedAttributes = [
        // html
        "acceptcharset" => "acceptCharset",
        "accept-charset" => "acceptCharset",
        "accesskey" => "accessKey",
        "allowfullscreen" => "allowFullScreen",
        "autocapitalize" => "autoCapitalize",
        "autocomplete" => "autoComplete",
        "autocorrect" => "autoCorrect",
        "autofocus" => "autoFocus",
        "autoplay" => "autoPlay",
        "autosave" => "autoSave",
        "cellpadding" => "cellPadding",
        "cellspacing" => "cellSpacing",
        "charset" => "charSet",
        "classid" => "classID",
        "colspan" => "colSpan",
        "contenteditable" => "contentEditable",
        "contextmenu" => "contextMenu",
        "controlslist" => "controlsList",
        "crossorigin" => "crossOrigin",
        "datetime" => "dateTime",
        "defaultchecked" => "defaultChecked",
        "defaultvalue" => "defaultValue",
        "enctype" => "encType",
        "for" => "htmlFor",
        "formmethod" => "formMethod",
        "formaction" => "formAction",
        "formenctype" => "formEncType",
        "formnovalidate" => "formNoValidate",
        "formtarget" => "formTarget",
        "frameborder" => "frameBorder",
        "hreflang" => "hrefLang",
        "htmlfor" => "htmlFor",
        "httpequiv" => "httpEquiv",
        "http-equiv" => "httpEquiv",
        "innerhtml" => "innerHTML",
        "inputmode" => "inputMode",
        "itemid" => "itemID",
        "itemprop" => "itemProp",
        "itemref" => "itemRef",
        "itemscope" => "itemScope",
        "itemtype" => "itemType",
        "keyparams" => "keyParams",
        "keytype" => "keyType",
        "marginwidth" => "marginWidth",
        "marginheight" => "marginHeight",
        "maxlength" => "maxLength",
        "mediagroup" => "mediaGroup",
        "minlength" => "minLength",
        "nomodule" => "noModule",
        "novalidate" => "noValidate",
        "playsinline" => "playsInline",
        "radiogroup" => "radioGroup",
        "readonly" => "readOnly",
        "referrerpolicy" => "referrerPolicy",
        "rowspan" => "rowSpan",
        "spellcheck" => "spellCheck",
        "srcdoc" => "srcDoc",
        "srclang" => "srcLang",
        "srcset" => "srcSet",
        "tabindex" => "tabIndex",
        "usemap" => "useMap",

        // svg
        "accentheight" => "accentHeight",
        "accent-height" => "accentHeight",
        "alignmentbaseline" => "alignmentBaseline",
        "alignment-baseline" => "alignmentBaseline",
        "allowreorder" => "allowReorder",
        "arabicform" => "arabicForm",
        "arabic-form" => "arabicForm",
        "attributename" => "attributeName",
        "attributetype" => "attributeType",
        "autoreverse" => "autoReverse",
        "basefrequency" => "baseFrequency",
        "baselineshift" => "baselineShift",
        "baseline-shift" => "baselineShift",
        "baseprofile" => "baseProfile",
        "calcmode" => "calcMode",
        "capheight" => "capHeight",
        "cap-height" => "capHeight",
        "clippath" => "clipPath",
        "clip-path" => "clipPath",
        "clippathunits" => "clipPathUnits",
        "cliprule" => "clipRule",
        "clip-rule" => "clipRule",
        "colorinterpolation" => "colorInterpolation",
        "color-interpolation" => "colorInterpolation",
        "colorinterpolationfilters" => "colorInterpolationFilters",
        "color-interpolation-filters" => "colorInterpolationFilters",
        "colorprofile" => "colorProfile",
        "color-profile" => "colorProfile",
        "colorrendering" => "colorRendering",
        "color-rendering" => "colorRendering",
        "contentscripttype" => "contentScriptType",
        "contentstyletype" => "contentStyleType",
        "diffuseconstant" => "diffuseConstant",
        "dominantbaseline" => "dominantBaseline",
        "dominant-baseline" => "dominantBaseline",
        "edgemode" => "edgeMode",
        "enablebackground" => "enableBackground",
        "enable-background" => "enableBackground",
        "externalresourcesrequired" => "externalResourcesRequired",
        "fillopacity" => "fillOpacity",
        "fill-opacity" => "fillOpacity",
        "fillrule" => "fillRule",
        "fill-rule" => "fillRule",
        "filterres" => "filterRes",
        "filterunits" => "filterUnits",
        "floodopacity" => "floodOpacity",
        "flood-opacity" => "floodOpacity",
        "floodcolor" => "floodColor",
        "flood-color" => "floodColor",
        "fontfamily" => "fontFamily",
        "font-family" => "fontFamily",
        "fontsize" => "fontSize",
        "font-size" => "fontSize",
        "fontsizeadjust" => "fontSizeAdjust",
        "font-size-adjust" => "fontSizeAdjust",
        "fontstretch" => "fontStretch",
        "font-stretch" => "fontStretch",
        "fontstyle" => "fontStyle",
        "font-style" => "fontStyle",
        "fontvariant" => "fontVariant",
        "font-variant" => "fontVariant",
        "fontweight" => "fontWeight",
        "font-weight" => "fontWeight",
        "glyphname" => "glyphName",
        "glyph-name" => "glyphName",
        "glyphorientationhorizontal" => "glyphOrientationHorizontal",
        "glyph-orientation-horizontal" => "glyphOrientationHorizontal",
        "glyphorientationvertical" => "glyphOrientationVertical",
        "glyph-orientation-vertical" => "glyphOrientationVertical",
        "glyphref" => "glyphRef",
        "gradienttransform" => "gradientTransform",
        "gradientunits" => "gradientUnits",
        "horizadvx" => "horizAdvX",
        "horiz-adv-x" => "horizAdvX",
        "horizoriginx" => "horizOriginX",
        "horiz-origin-x" => "horizOriginX",
        "imagerendering" => "imageRendering",
        "image-rendering" => "imageRendering",
        "kernelmatrix" => "kernelMatrix",
        "kernelunitlength" => "kernelUnitLength",
        "keypoints" => "keyPoints",
        "keysplines" => "keySplines",
        "keytimes" => "keyTimes",
        "lengthadjust" => "lengthAdjust",
        "letterspacing" => "letterSpacing",
        "letter-spacing" => "letterSpacing",
        "lightingcolor" => "lightingColor",
        "lighting-color" => "lightingColor",
        "limitingconeangle" => "limitingConeAngle",
        "markerend" => "markerEnd",
        "marker-end" => "markerEnd",
        "markerheight" => "markerHeight",
        "markermid" => "markerMid",
        "marker-mid" => "markerMid",
        "markerstart" => "markerStart",
        "marker-start" => "markerStart",
        "markerunits" => "markerUnits",
        "markerwidth" => "markerWidth",
        "maskcontentunits" => "maskContentUnits",
        "maskunits" => "maskUnits",
        "numoctaves" => "numOctaves",
        "overlineposition" => "overlinePosition",
        "overline-position" => "overlinePosition",
        "overlinethickness" => "overlineThickness",
        "overline-thickness" => "overlineThickness",
        "paintorder" => "paintOrder",
        "paint-order" => "paintOrder",
        "panose-1" => "panose1",
        "pathlength" => "pathLength",
        "patterncontentunits" => "patternContentUnits",
        "patterntransform" => "patternTransform",
        "patternunits" => "patternUnits",
        "pointerevents" => "pointerEvents",
        "pointer-events" => "pointerEvents",
        "pointsatx" => "pointsAtX",
        "pointsaty" => "pointsAtY",
        "pointsatz" => "pointsAtZ",
        "preservealpha" => "preserveAlpha",
        "preserveaspectratio" => "preserveAspectRatio",
        "primitiveunits" => "primitiveUnits",
        "refx" => "refX",
        "refy" => "refY",
        "renderingintent" => "renderingIntent",
        "rendering-intent" => "renderingIntent",
        "repeatcount" => "repeatCount",
        "repeatdur" => "repeatDur",
        "requiredextensions" => "requiredExtensions",
        "requiredfeatures" => "requiredFeatures",
        "shaperendering" => "shapeRendering",
        "shape-rendering" => "shapeRendering",
        "specularconstant" => "specularConstant",
        "specularexponent" => "specularExponent",
        "spreadmethod" => "spreadMethod",
        "startoffset" => "startOffset",
        "stddeviation" => "stdDeviation",
        "stitchtiles" => "stitchTiles",
        "stopcolor" => "stopColor",
        "stop-color" => "stopColor",
        "stopopacity" => "stopOpacity",
        "stop-opacity" => "stopOpacity",
        "strikethroughposition" => "strikethroughPosition",
        "strikethrough-position" => "strikethroughPosition",
        "strikethroughthickness" => "strikethroughThickness",
        "strikethrough-thickness" => "strikethroughThickness",
        "strokedasharray" => "strokeDasharray",
        "stroke-dasharray" => "strokeDasharray",
        "strokedashoffset" => "strokeDashoffset",
        "stroke-dashoffset" => "strokeDashoffset",
        "strokelinecap" => "strokeLinecap",
        "stroke-linecap" => "strokeLinecap",
        "strokelinejoin" => "strokeLinejoin",
        "stroke-linejoin" => "strokeLinejoin",
        "strokemiterlimit" => "strokeMiterlimit",
        "stroke-miterlimit" => "strokeMiterlimit",
        "strokewidth" => "strokeWidth",
        "stroke-width" => "strokeWidth",
        "strokeopacity" => "strokeOpacity",
        "stroke-opacity" => "strokeOpacity",
        "suppresscontenteditablewarning" => "suppressContentEditableWarning",
        "suppresshydrationwarning" => "suppressHydrationWarning",
        "surfacescale" => "surfaceScale",
        "systemlanguage" => "systemLanguage",
        "tablevalues" => "tableValues",
        "targetx" => "targetX",
        "targety" => "targetY",
        "textanchor" => "textAnchor",
        "text-anchor" => "textAnchor",
        "textdecoration" => "textDecoration",
        "text-decoration" => "textDecoration",
        "textlength" => "textLength",
        "textrendering" => "textRendering",
        "text-rendering" => "textRendering",
        "underlineposition" => "underlinePosition",
        "underline-position" => "underlinePosition",
        "underlinethickness" => "underlineThickness",
        "underline-thickness" => "underlineThickness",
        "unicodebidi" => "unicodeBidi",
        "unicode-bidi" => "unicodeBidi",
        "unicoderange" => "unicodeRange",
        "unicode-range" => "unicodeRange",
        "unitsperem" => "unitsPerEm",
        "units-per-em" => "unitsPerEm",
        "valphabetic" => "vAlphabetic",
        "v-alphabetic" => "vAlphabetic",
        "vectoreffect" => "vectorEffect",
        "vector-effect" => "vectorEffect",
        "vertadvy" => "vertAdvY",
        "vert-adv-y" => "vertAdvY",
        "vertoriginx" => "vertOriginX",
        "vert-origin-x" => "vertOriginX",
        "vertoriginy" => "vertOriginY",
        "vert-origin-y" => "vertOriginY",
        "vhanging" => "vHanging",
        "v-hanging" => "vHanging",
        "videographic" => "vIdeographic",
        "v-ideographic" => "vIdeographic",
        "viewbox" => "viewBox",
        "viewtarget" => "viewTarget",
        "vmathematical" => "vMathematical",
        "v-mathematical" => "vMathematical",
        "wordspacing" => "wordSpacing",
        "word-spacing" => "wordSpacing",
        "writingmode" => "writingMode",
        "writing-mode" => "writingMode",
        "xchannelselector" => "xChannelSelector",
        "xheight" => "xHeight",
        "x-height" => "xHeight",
        "xlinkactuate" => "xlinkActuate",
        "xlink:actuate" => "xlinkActuate",
        "xlinkarcrole" => "xlinkArcrole",
        "xlink:arcrole" => "xlinkArcrole",
        "xlinkhref" => "xlinkHref",
        "xlink:href" => "xlinkHref",
        "xlinkrole" => "xlinkRole",
        "xlink:role" => "xlinkRole",
        "xlinkshow" => "xlinkShow",
        "xlink:show" => "xlinkShow",
        "xlinktitle" => "xlinkTitle",
        "xlink:title" => "xlinkTitle",
        "xlinktype" => "xlinkType",
        "xlink:type" => "xlinkType",
        "xmlbase" => "xmlBase",
        "xml:base" => "xmlBase",
        "xmllang" => "xmlLang",
        "xml:lang" => "xmlLang",
        "xml:space" => "xmlSpace",
        "xmlnsxlink" => "xmlnsXlink",
        "xmlns:xlink" => "xmlnsXlink",
        "xmlspace" => "xmlSpace",
        "ychannelselector" => "yChannelSelector",
        "zoomandpan" => "zoomAndPan",
    ];

    public function render($name, $props = null)
    {
        $props = $this->propsFrom($props);

        if ($function = globalFunctionMatching($name)) {
            return call_user_func($function, $props);
        }

        if ($class = globalClassMatching($name)) {
            return (new $class($props))->render();
        }

        if (!in_array($name, $this->allowedTags)) {
            throw new Exception("{$name} is not an allowed tag");
        }

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
            return $this->format("<{$open} />");
        }

        return $this->format("<{$open}>{$children}</{$name}>");
    }

    public function propsFrom($props = null)
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
            $properKey = $key;

            $reversedAttributes = array_reverse($this->renamedAttributes);

            if (isset($reversedAttributes[$key])) {
                trigger_error("{$key} should be replaced with {$reversedAttributes[$key]}");

                $properKey = $reversedAttributes[$key];
            }

            $flippedAttributes = array_flip($reversedAttributes);

            if (isset($flippedAttributes[$properKey])) {
                $key = $flippedAttributes[$properKey];
            }

            if (is_callable($value)) {
                $value = $value();
            }

            $attributes[] = "{$key}=\"{$value}\"";
        }

        return join(" ", $attributes);
    }

    public function format($markup)
    {
        $indenter = new Indenter();
        return $indenter->indent($markup);
    }
}
