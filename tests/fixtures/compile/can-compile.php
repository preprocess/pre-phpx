<?php

function Error($props)
{
    return render("div", [
        "className" => "outer" . cls("special"),
        "render" => function ($error) {
            return render("span", [
                "className" => "error",
                "children" => $error,
            ]);
        },
        "children" => [
            render("span", [
                "className" => "icon",
            ]),
            render("h4", [
                "children" => "Error",
            ]),
            "You forgot the",
            $props->name,
            "field",
        ],
    ]);
}

function ErrorRenderer($props)
{
    return render("Error", [
        "children" => function ($error) {
            return render("span", [
                "children" => $error,
            ]);
        },
    ]);
}

function InputRenderer($props)
{
    return render("input", [
        "type" => $props->type,
    ]);
}
