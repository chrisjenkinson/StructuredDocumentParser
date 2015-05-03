<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\Token;

use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokenSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('Something', ['all' => 'value']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\Token\Token');
    }

    public function it_has_a_type()
    {
        $this->getType()->shouldReturn('Something');
    }

    public function it_has_values()
    {
        $this->getValue('all')->shouldReturn('value');
    }

    public function it_throws_an_exeption_if_no_key()
    {
        $this->shouldThrow('\RuntimeException')->duringGetValue('nonexistent');
    }

    public function it_can_return_all_values()
    {
        $this->getValues()->shouldReturn(['all' => 'value']);
    }

    public function it_can_tell_if_it_has_a_key()
    {
        $this->hasKey('all')->shouldReturn(true);
    }

    public function it_stores_position_information(TokenPosition $position)
    {
        $this->setPosition($position);

        $this->getPosition()->shouldBeAnInstanceOf(TokenPosition::class);
    }

    public function it_casts_to_a_string()
    {
        $this->__toString()->shouldReturn('Something (value)');
    }
}
