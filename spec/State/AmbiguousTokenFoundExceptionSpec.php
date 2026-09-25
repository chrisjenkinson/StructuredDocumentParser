<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
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
            [$matchedText1, $matchedText2],
            new TokenPosition(12, 5)
        );

        $matchedText1->getAll()->willReturn(['all' => "## Heading\n"]);
        $matchedText2->getAll()->willReturn(['all' => '##']);
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

    public function it_has_the_position(): void
    {
        $this->getPosition()->shouldBeLike(new TokenPosition(12, 5));
    }

    public function it_shows_the_position_and_each_match_in_the_message(): void
    {
        $this->getMessage()->shouldReturn('Ambiguous token found with state stateName at line 12, column 5: matchers calledMatcher1 ("## Heading"), calledMatcher2 ("##")');
    }
}
