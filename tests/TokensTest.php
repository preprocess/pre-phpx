<?php

/**
 * @covers Pre\Phpx\Parser::tokens
 */
class TokensTest extends TestCase
{
    public function test_can_tokenise()
    {
        $this->assertEquals(
            $this->fixtureData("can-tokenise"),
            $this->parser()->tokens($this->fixtureCode("can-tokenise"))
        );
    }

    public function test_can_ignore_markup_in_string() {
        $this->assertEquals(
            $this->fixtureData("can-ignore-markup-in-string"),
            $this->parser()->tokens($this->fixtureCode("can-ignore-markup-in-string"))
        );
    }

    public function test_can_handle_simple_props() {
        $this->assertEquals(
            $this->fixtureData("can-handle-simple-props"),
            $this->parser()->tokens($this->fixtureCode("can-handle-simple-props"))
        );
    }

    public function test_can_handle_complex_props() {
        $this->assertEquals(
            $this->fixtureData("can-handle-complex-props"),
            $this->parser()->tokens($this->fixtureCode("can-handle-complex-props"))
        );
    }
}
