<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use PhpSpec\ObjectBehavior;
use RuntimeException;

class NoTokenFoundExceptionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('stateName', 50, "## Heading\nmore text", new TokenPosition(12, 5));
    }

    public function it_is_an_exception(): void
    {
        $this->shouldHaveType(RuntimeException::class);
    }

    public function it_has_the_state_name(): void
    {
        $this->getStateName()->shouldReturn('stateName');
    }

    public function it_has_the_current_position(): void
    {
        $this->getCurrentPosition()->shouldReturn(50);
    }

    public function it_has_the_remaining_text(): void
    {
        $this->getRemainingText()->shouldReturn("## Heading\nmore text");
    }

    public function it_has_the_position(): void
    {
        $this->getPosition()->shouldBeLike(new TokenPosition(12, 5));
    }

    public function it_shows_the_position_and_the_rest_of_the_line_in_the_message(): void
    {
        $this->getMessage()->shouldReturn('No token found with state stateName at line 12, column 5: "## Heading"');
    }

    public function it_caps_the_excerpt_at_100_characters(): void
    {
        $this->beConstructedWith('stateName', 50, str_repeat('é', 150), new TokenPosition(12, 5));

        $this->getMessage()->shouldReturn('No token found with state stateName at line 12, column 5: "' . str_repeat('é', 100) . '…"');
    }

    public function it_shows_a_newline_when_the_text_starts_with_one(): void
    {
        $this->beConstructedWith('stateName', 50, "\nmore text", new TokenPosition(12, 5));

        $this->getMessage()->shouldReturn('No token found with state stateName at line 12, column 5: "\\n"');
    }
}
