<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Token;

use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;
use PhpSpec\ObjectBehavior;

class TokenStreamSpec extends ObjectBehavior
{
    public function it_can_add_tokens(TokenInterface $token): void
    {
        $this->add($token);

        $this->shouldHaveCount(1);
    }

    public function it_can_return_current_token(TokenInterface $token): void
    {
        $this->add($token);

        $this->getCurrentToken()->shouldReturn($token);
    }

    public function it_returns_the_first_token_in_stream_by_default(TokenInterface $token1, TokenInterface $token2): void
    {
        $this->add($token1);
        $this->add($token2);

        $this->getCurrentToken()->shouldReturn($token1);
    }

    public function it_returns_null_if_there_are_no_tokens(): void
    {
        $this->getCurrentToken()->shouldReturn(null);
    }

    public function it_can_look_ahead(TokenInterface $token1, TokenInterface $token2): void
    {
        $this->add($token1);
        $this->add($token2);

        $this->lookAhead()->shouldReturn($token2);
    }

    public function it_returns_null_if_looking_ahead_too_far(): void
    {
        $this->lookAhead()->shouldReturn(null);
    }

    public function it_is_countable(): void
    {
        $this->shouldImplement('\Countable');
    }

    public function it_throws_an_exception_if_trying_to_consume_tokens_and_there_are_none(): void
    {
        $this->shouldThrow('\RuntimeException')->duringConsumeToken();
    }

    public function it_shortens_the_stream_when_consuming_tokens(TokenInterface $token1, TokenInterface $token2): void
    {
        $this->add($token1);
        $this->add($token2);

        $this->shouldHaveCount(2);

        $this->consumeToken()->shouldReturn($token1);

        $this->shouldHaveCount(1);

        $this->consumeToken()->shouldReturn($token2);

        $this->shouldHaveCount(0);
    }

    public function it_can_expect_a_token_type(TokenInterface $token): void
    {
        $token->getType()->willReturn('type1');

        $this->add($token);

        $this->expectTokenType('type1')->shouldReturn(true);
    }

    public function it_can_expect_alternative_token_types(TokenInterface $token): void
    {
        $token->getType()->willReturn('type1');

        $this->add($token);

        $this->expectTokenTypes(['type1', 'type2'])->shouldReturn(true);
    }

    public function it_throws_an_exception_if_wrong_token_type_is_found(TokenInterface $token): void
    {
        $token->getType()->willReturn('type1');

        $this->add($token);

        $this->shouldThrow('\RuntimeException')->duringExpectTokenType('type2');

        $this->shouldThrow('\RuntimeException')->duringExpectTokenTypes(['type2']);
    }

    public function it_throws_an_exception_when_expecting_token_type_if_at_stream_end(): void
    {
        $this->shouldThrow('\RuntimeException')->duringExpectTokenType('type1');

        $this->shouldThrow('\RuntimeException')->duringExpectTokenTypes(['type1']);
    }

    public function it_casts_to_a_string(TokenInterface $token1, TokenInterface $token2): void
    {
        $this->add($token1);
        $this->add($token2);

        $token1->__toString()->willReturn('SomethingToken (1)');
        $token2->__toString()->willReturn('SomethingToken (2)');

        $this->__toString()->shouldReturn("SomethingToken (1)\nSomethingToken (2)");
    }
}
