<?php

namespace Example {
    function MyComponent()
    {
        return render("div", [
            "children" => "hello world"
        ]);
    }
}

namespace {
    function render($name, $props = null)
    {
        print $name;
    }

    print render("Example\MyComponent", []);
}
