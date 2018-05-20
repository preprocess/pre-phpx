<?php

/**
 * @covers Pre\Phpx\Parser::tokens
 */
class TokensTest extends TestCase
{
    public function test_can_tokenise()
    {
        $code = "
            function Hello(\$props) {
                return <div>Hello {\$props->name}</div>;
            }
        ";

        $expected = [
            "function Hello(\$props) {
                return",
            [ "tag" => "<div>", "started" => 65 ],
            "Hello",
            [ "expression" => "\$props->name", "started" => 84 ],
            "",
            [ "tag" => "</div>", "started" => 89 ],
            ";
            }",
        ];

        $actual = $this->parser()->tokens($code);

        $this->assertEquals($expected, $actual);
    }
}
