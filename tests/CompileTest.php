<?php

/**
 * @covers Pre\Phpx\Parser::__construct
 * @covers Pre\Phpx\Parser::translate
 * @covers Pre\Phpx\Parser::format
 * @covers Pre\Phpx\Printer
 */
class CompileTest extends TestCase
{
    public function test_can_compile()
    {
        $this->assertEquals(
            $this->fixtureCode("compile/can-compile", "php"),
            \Pre\Phpx\compile($this->fixtureCode("compile/can-compile", "pre"))
        );
    }
}
