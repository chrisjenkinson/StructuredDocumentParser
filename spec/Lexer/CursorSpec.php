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

    public function it_starts_at_line_one_column_one(): void
    {
        $this->getLine()->shouldReturn(1);
        $this->getColumn()->shouldReturn(1);
    }

    public function it_tracks_the_column_within_a_line(): void
    {
        $this->advance(5);

        $this->getLine()->shouldReturn(1);
        $this->getColumn()->shouldReturn(6);
    }

    public function it_tracks_the_line_and_column_across_newlines(): void
    {
        $this->beConstructedWith("ab\ncd\nefg");

        $this->advance(4);

        $this->getLine()->shouldReturn(2);
        $this->getColumn()->shouldReturn(2);

        $this->advance(4);

        $this->getLine()->shouldReturn(3);
        $this->getColumn()->shouldReturn(3);
    }

    public function it_counts_columns_in_characters(): void
    {
        $this->beConstructedWith('éé€x');

        $this->advance(3);

        $this->getColumn()->shouldReturn(4);
    }

    public function it_returns_the_remaining_text_after_advancing_over_multibyte_characters(): void
    {
        $this->beConstructedWith("é€\nxé");

        $this->advance(3);

        $this->getRemainingText()->shouldReturn('xé');
        $this->getCurrentPosition()->shouldReturn(3);

        $this->advance(2);

        $this->isEndOfText()->shouldReturn(true);
    }

    public function it_can_start_at_a_given_line_and_column(): void
    {
        $this->beConstructedWith("ab\ncd", 10, 5);

        $this->getLine()->shouldReturn(10);
        $this->getColumn()->shouldReturn(5);

        $this->advance(1);

        $this->getColumn()->shouldReturn(6);

        $this->advance(3);

        $this->getLine()->shouldReturn(11);
        $this->getColumn()->shouldReturn(2);
    }
}
