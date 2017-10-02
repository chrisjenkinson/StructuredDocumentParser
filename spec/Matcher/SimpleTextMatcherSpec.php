<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Matcher;

use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use PhpSpec\ObjectBehavior;

class SimpleTextMatcherSpec extends ObjectBehavior
{
    public function it_has_a_name(): void
    {
        $this->getName()->shouldReturn('SimpleTextMatcher');
    }

    public function it_matches_any_text(): void
    {
        $matchedText = new MatchedText(['all' => '1234']);

        $this->match('1234')->shouldBeLike($matchedText);
    }

    public function it_does_not_match_an_empty_string(): void
    {
        $this->match('')->shouldReturn(null);
    }
}
