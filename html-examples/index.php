<?php

require __DIR__ . "/../vendor/autoload.php";

\Pre\Plugin\addCompiler("examples", "\Pre\Phpx\Parser::compile");

\Pre\Plugin\process(__DIR__ . "/index-fixture.pre");

// print \Pre\Phpx\Html\render("a", [
//     "className" => ["one", "two", function () {
//         return "three";
//     }],
//     "style" => [
//         "font-weight" => "bold",
//         "font-family" => function () {
//             return "arial";
//         },
//     ],
//     "children" => [
//         "this is cool",
//     ],
// ]);

// print \Pre\Phpx\Html\render("input", [
//     "className" => ["one", "two", function () {
//         return "three";
//     }],
//     "style" => [
//         "font-weight" => "bold",
//         "font-family" => function () {
//             return "arial";
//         },
//     ],
// ]);
