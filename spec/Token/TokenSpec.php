<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Token;

use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use PhpSpec\ObjectBehavior;

class TokenSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('Something', ['all' => 'value']);
    }

    public function it_has_a_type(): void
    {
        $this->getType()->shouldReturn('Something');
    }

    public function it_has_values(): void
    {
        $this->getValue('all')->shouldReturn('value');
    }

    public function it_throws_an_exception_if_no_key(): void
    {
        $this->shouldThrow(\RuntimeException::class)->duringGetValue('nonexistent');
    }

    public function it_can_return_all_values(): void
    {
        $this->getValues()->shouldReturn(['all' => 'value']);
    }

    public function it_can_tell_if_it_has_a_key(): void
    {
        $this->hasKey('all')->shouldReturn(true);
    }

    public function it_stores_position_information(TokenPosition $position): void
    {
        $this->setPosition($position);

        $this->getPosition()->shouldBeAnInstanceOf(TokenPosition::class);
    }

    public function it_casts_to_a_string(): void
    {
        $this->__toString()->shouldReturn('Something (value)');
    }
}
