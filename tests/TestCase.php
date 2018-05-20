<?php

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function parser() {
        return new Pre\Phpx\Parser();
    }
}
