<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\Matcher;

use PhpSpec\ObjectBehavior;

class MatchedTextSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(['all' => '1234']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText');
    }

    public function it_can_get_individual_elements()
    {
        $this->get('all')->shouldReturn('1234');
    }

    public function it_returns_null_if_the_element_does_not_exist()
    {
        $this->get('nonexistent')->shouldReturn(null);
    }

    public function it_can_get_all_elements()
    {
        $this->getAll()->shouldReturn(['all' => '1234']);
    }
}
