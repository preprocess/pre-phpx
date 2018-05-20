<?php

require __DIR__ . "/../vendor/autoload.php";

// you don't need to do this in your projects
// the composer plugin will automatically add the compiler there
\Pre\Plugin\addCompiler("examples", "\Pre\Phpx\Parser::compile");

// preprocess the fixtures, so they have valid render syntax
\Pre\Plugin\process(__DIR__ . "/custom-renderer-fixture.pre");

print render(Fields::class);
