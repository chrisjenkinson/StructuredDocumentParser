<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Lexer\Cursor;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatcherInterface;
use chrisjenkinson\StructuredDocumentParser\State\AmbiguousTokenFoundException;
use chrisjenkinson\StructuredDocumentParser\State\NoTokenFoundException;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;
use PhpSpec\ObjectBehavior;

class InitialStateSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\State\InitialState');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('InitialState');
    }

    public function it_throws_an_exception_if_there_is_an_ambiguous_token(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher1, MatcherInterface $matcher2)
    {
        $cursor->getRemainingText()->willReturn('remainingText');

        $this->registerMatcher($matcher1);
        $this->registerMatcher($matcher2);

        $matcher1->getName()->willReturn('matcher1');
        $matcher2->getName()->willReturn('matcher2');

        $matcher1->match('remainingText')->willReturn(true);
        $matcher2->match('remainingText')->willReturn(true);

        $this->shouldThrow(AmbiguousTokenFoundException::class)->duringFindMatchingToken($lexer, $cursor);
    }

    public function it_throws_an_exception_if_there_is_no_token(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher)
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);

        $this->registerMatcher($matcher);

        $matcher->match('remainingText')->willReturn(false);

        $this->shouldThrow(NoTokenFoundException::class)->duringFindMatchingToken($lexer, $cursor);
    }

    public function it_returns_a_token(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher, MatchedText $matchedText)
    {
        $cursor->getRemainingText()->willReturn('remainingText');

        $this->registerMatcher($matcher);

        $matcher->match('remainingText')->willReturn($matchedText);
        $matcher->getName()->willReturn('matcher');

        $matchedText->getAll()->willReturn(['all' => 'remainingText']);

        $this->findMatchingToken($lexer, $cursor)->shouldReturnAnInstanceOf(TokenInterface::class);
    }

    public function it_calls_a_callback(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher, MatchedText $matchedText)
    {
        $cursor->getRemainingText()->willReturn('remainingText');

        $lexer->getState()->shouldBeCalled();

        $this->registerMatcher($matcher, function (Lexer $lexer) {$lexer->getState();});

        $matcher->match('remainingText')->willReturn($matchedText);
        $matcher->getName()->willReturn('matcher');

        $matchedText->getAll()->willReturn(['all' => 'remainingText']);

        $this->findMatchingToken($lexer, $cursor)->shouldReturnAnInstanceOf(TokenInterface::class);
    }
}
