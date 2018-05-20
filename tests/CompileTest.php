<?php

/**
 * @covers Pre\Phpx\Parser::compile
 */
class CompileTest extends TestCase
{
    public function test_can_compile()
    {
        $this->assertEquals(
            $this->fixtureData("compile/can-compile"),
            \Pre\Phpx\Parser::compile($this->fixtureCode("compile/can-compile"))
        );
    }
}
