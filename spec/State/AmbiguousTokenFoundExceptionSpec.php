<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use PhpSpec\ObjectBehavior;
use RuntimeException;

class AmbiguousTokenFoundExceptionSpec extends ObjectBehavior
{
    public function let(MatchedText $matchedText1, MatchedText $matchedText2): void
    {
        $this->beConstructedWith(
            'stateName',
            'text',
            ['calledMatcher1', 'calledMatcher2'],
            [$matchedText1, $matchedText2]
        );

        $matchedText1->getAll()->willReturn([]);
        $matchedText2->getAll()->willReturn([]);
    }

    public function it_is_an_exception(): void
    {
        $this->shouldHaveType(RuntimeException::class);
    }

    public function it_has_the_state_name(): void
    {
        $this->getStateName()->shouldReturn('stateName');
    }

    public function it_has_the_text(): void
    {
        $this->getText()->shouldReturn('text');
    }

    public function it_has_the_called_matchers(): void
    {
        $this->getCalledMatchers()->shouldReturn(['calledMatcher1', 'calledMatcher2']);
    }

    public function it_has_the_matched_tokens(MatchedText $matchedText1, MatchedText $matchedText2): void
    {
        $this->getMatchedTokens()->shouldReturn([$matchedText1, $matchedText2]);
    }
}
