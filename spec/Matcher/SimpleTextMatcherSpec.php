<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\Matcher;

use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SimpleTextMatcherSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\Matcher\SimpleTextMatcher');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('SimpleTextMatcher');
    }

    public function it_matches_any_text()
    {
        $matchedText = new MatchedText(['all' => '1234']);

        $this->match('1234')->shouldBeLike($matchedText);
    }

    public function it_does_not_match_an_empty_string()
    {
        $this->match('')->shouldReturn(null);
    }
}
