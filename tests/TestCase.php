<?php

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function parser() {
        return new Pre\Phpx\Parser();
    }

    protected function fixtureCode($path)
    {
        return trim(file_get_contents(__DIR__ . "/fixtures/{$path}.txt"));
    }

    protected function fixtureData($path)
    {
        return require __DIR__ . "/fixtures/{$path}.php";
    }
}
