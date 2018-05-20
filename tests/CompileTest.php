<?php

/**
 * @covers Pre\Phpx\Parser::compile
 * @covers Pre\Phpx\Parser::translate
 */
class CompileTest extends TestCase
{
    public function test_can_compile()
    {
        $this->assertEquals(
            $this->fixtureCode("compile/can-compile", "php"),
            \Pre\Phpx\Parser::compile($this->fixtureCode("compile/can-compile", "pre"))
        );
    }
}
