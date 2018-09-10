<?php

/**
 * @covers Pre\Phpx\compile
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

    public function test_supports_nested_component_names()
    {
        $this->assertEquals(
            $this->fixtureCode("compile/supports-nested-component-names", "php"),
            \Pre\Phpx\compile($this->fixtureCode("compile/supports-nested-component-names", "pre"))
        );
    }
}
