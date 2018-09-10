<?php

/**
 * @covers Pre\Phpx\Parser::tokens
 */
class TokensTest extends TestCase
{
    public function test_can_handle_expressions()
    {
        $this->assertEquals(
            $this->fixtureData("tokens/can-handle-expressions"),
            $this->parser()->tokens($this->fixtureCode("tokens/can-handle-expressions"))
        );
    }

    public function test_can_ignore_markup_in_string() {
        $this->assertEquals(
            $this->fixtureData("tokens/can-ignore-markup-in-string"),
            $this->parser()->tokens($this->fixtureCode("tokens/can-ignore-markup-in-string"))
        );
    }

    public function test_can_handle_simple_props() {
        $this->assertEquals(
            $this->fixtureData("tokens/can-handle-simple-props"),
            $this->parser()->tokens($this->fixtureCode("tokens/can-handle-simple-props"))
        );
    }

    public function test_can_handle_complex_props() {
        $this->assertEquals(
            $this->fixtureData("tokens/can-handle-complex-props"),
            $this->parser()->tokens($this->fixtureCode("tokens/can-handle-complex-props"))
        );
    }

    public function test_can_handle_self_closing_tags()
    {
        $this->assertEquals(
            $this->fixtureData("tokens/can-handle-self-closing-tags"),
            $this->parser()->tokens($this->fixtureCode("tokens/can-handle-self-closing-tags"))
        );
    }
}
