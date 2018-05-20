<?php

/**
 * @covers Pre\Phpx\Parser::nodes
 * @covers Pre\Phpx\Parser::removeParents
 */
class NodesTest extends TestCase
{
    public function test_can_arrange_nodes()
    {
        $this->assertEquals(
            $this->fixtureData("nodes/can-arrange-nodes"),
            $this->parser()->nodes($this->parser()->tokens($this->fixtureCode("nodes/can-arrange-nodes")))
        );
    }

    public function test_can_handle_nested_nodes()
    {
        $this->assertEquals(
            $this->fixtureData("nodes/can-handle-nested-nodes"),
            $this->parser()->nodes($this->parser()->tokens($this->fixtureCode("nodes/can-handle-nested-nodes")))
        );
    }

    /**
     * @expectedException Exception
     */
    public function test_can_detect_missing_opening_tag()
    {
        $this->parser()->nodes($this->parser()->tokens($this->fixtureCode("nodes/can-detect-missing-opening-tag")));
    }

    /**
     * @expectedException Exception
     */
    public function test_can_detect_different_opening_tag()
    {
        $this->parser()->nodes($this->parser()->tokens($this->fixtureCode("nodes/can-detect-different-opening-tag")));
    }
}
