<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Lexer;

use PhpSpec\ObjectBehavior;

class CursorSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('Something 1234');
    }

    public function it_returns_false_if_not_at_end_of_text(): void
    {
        $this->isEndOfText()->shouldReturn(false);
    }

    public function it_can_advance(): void
    {
        $this->advance(5);

        $this->getCurrentPosition()->shouldEqual(5);
    }

    public function it_returns_true_at_end_of_text(): void
    {
        $this->advance(mb_strlen('Something 1234'));

        $this->isEndOfText()->shouldReturn(true);
    }

    public function it_can_return_remaining_text(): void
    {
        $this->getRemainingText()->shouldReturn('Something 1234');

        $this->advance(3);

        $this->getRemainingText()->shouldReturn('ething 1234');
    }

    public function it_returns_an_empty_string_when_returning_remaining_text_at_end(): void
    {
        $this->advance(mb_strlen('Something 1234'));

        $this->getRemainingText()->shouldReturn('');
    }
}
