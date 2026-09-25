<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use chrisjenkinson\StructuredDocumentParser\Lexer\Cursor;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatcherInterface;
use chrisjenkinson\StructuredDocumentParser\State\AmbiguousTokenFoundException;
use chrisjenkinson\StructuredDocumentParser\State\InvalidMatchedTextException;
use chrisjenkinson\StructuredDocumentParser\State\NoTokenFoundException;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use PhpSpec\ObjectBehavior;

class InitialStateSpec extends ObjectBehavior
{
    public function it_has_a_name(): void
    {
        $this->getName()->shouldReturn('InitialState');
    }

    public function it_throws_an_exception_if_there_is_an_ambiguous_token(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher1, MatcherInterface $matcher2, MatchedText $matchedText): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $matcher1->getName()->willReturn('matcher1');
        $matcher2->getName()->willReturn('matcher2');

        $matcher1->match('remainingText')->willReturn($matchedText);
        $matcher2->match('remainingText')->willReturn($matchedText);

        $matchedText->getAll()->willReturn(['all' => 'remaining']);

        $this->registerMatcher($matcher1);
        $this->registerMatcher($matcher2);

        $this->shouldThrow(AmbiguousTokenFoundException::class)->duringFindMatchingToken($lexer, $cursor);
    }

    public function it_throws_an_exception_if_there_is_no_token(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $this->registerMatcher($matcher);

        $matcher->match('remainingText')->willReturn(null);

        $this->shouldThrow(NoTokenFoundException::class)->duringFindMatchingToken($lexer, $cursor);
    }

    public function it_returns_a_token(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher, MatchedText $matchedText): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $this->registerMatcher($matcher);

        $matcher->match('remainingText')->willReturn($matchedText);
        $matcher->getName()->willReturn('matcher');

        $matchedText->getAll()->willReturn(['all' => 'remainingText']);

        $token = $this->findMatchingToken($lexer, $cursor);

        $token->shouldReturnAnInstanceOf(TokenInterface::class);
        $token->getPosition()->shouldBeLike(new TokenPosition(4, 2));
    }

    public function it_strips_the_matcher_suffix_from_the_token_type(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher, MatchedText $matchedText): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $this->registerMatcher($matcher);

        $matcher->match('remainingText')->willReturn($matchedText);
        $matcher->getName()->willReturn('HeadingMatcher');

        $matchedText->getAll()->willReturn(['all' => 'remainingText']);

        $this->findMatchingToken($lexer, $cursor)->getType()->shouldReturn('Heading');
    }

    public function it_uses_the_whole_matcher_name_as_the_token_type_without_a_matcher_suffix(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher, MatchedText $matchedText): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $this->registerMatcher($matcher);

        $matcher->match('remainingText')->willReturn($matchedText);
        $matcher->getName()->willReturn('Heading');

        $matchedText->getAll()->willReturn(['all' => 'remainingText']);

        $this->findMatchingToken($lexer, $cursor)->getType()->shouldReturn('Heading');
    }

    public function it_throws_if_the_matched_text_has_no_all_key(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher, MatchedText $matchedText): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $this->registerMatcher($matcher);

        $matcher->match('remainingText')->willReturn($matchedText);
        $matcher->getName()->willReturn('HeadingMatcher');

        $matchedText->getAll()->willReturn(['heading' => 'remainingText']);

        $this->shouldThrow(new InvalidMatchedTextException('HeadingMatcher', 'it has no "all" key'))->duringFindMatchingToken($lexer, $cursor);
    }

    public function it_throws_if_the_all_key_is_not_a_string(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher, MatchedText $matchedText): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $this->registerMatcher($matcher);

        $matcher->match('remainingText')->willReturn($matchedText);
        $matcher->getName()->willReturn('HeadingMatcher');

        $matchedText->getAll()->willReturn(['all' => 13]);

        $this->shouldThrow(new InvalidMatchedTextException('HeadingMatcher', 'its "all" value is not a string'))->duringFindMatchingToken($lexer, $cursor);
    }

    public function it_throws_if_the_all_value_is_not_at_the_start_of_the_text(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher, MatchedText $matchedText): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $this->registerMatcher($matcher);

        $matcher->match('remainingText')->willReturn($matchedText);
        $matcher->getName()->willReturn('HeadingMatcher');

        $matchedText->getAll()->willReturn(['all' => 'Text']);

        $this->shouldThrow(new InvalidMatchedTextException('HeadingMatcher', 'its "all" value is not at the start of the text'))->duringFindMatchingToken($lexer, $cursor);
    }

    public function it_reports_a_misplaced_match_rather_than_an_ambiguous_token(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher1, MatcherInterface $matcher2, MatchedText $matchedText1, MatchedText $matchedText2): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $this->registerMatcher($matcher1);
        $this->registerMatcher($matcher2);

        $matcher1->match('remainingText')->willReturn($matchedText1);
        $matcher1->getName()->willReturn('WordMatcher');
        $matcher2->match('remainingText')->willReturn($matchedText2);
        $matcher2->getName()->willReturn('HeadingMatcher');

        $matchedText1->getAll()->willReturn(['all' => 'remaining']);
        $matchedText2->getAll()->willReturn(['all' => 'Text']);

        $this->shouldThrow(new InvalidMatchedTextException('HeadingMatcher', 'its "all" value is not at the start of the text'))->duringFindMatchingToken($lexer, $cursor);
    }

    public function it_calls_a_callback(Lexer $lexer, Cursor $cursor, MatcherInterface $matcher, MatchedText $matchedText): void
    {
        $cursor->getRemainingText()->willReturn('remainingText');
        $cursor->getCurrentPosition()->willReturn(0);
        $cursor->getLine()->willReturn(4);
        $cursor->getColumn()->willReturn(2);

        $lexer->getState()->shouldBeCalled();

        $this->registerMatcher($matcher, function (Lexer $lexer): void {
            $lexer->getState();
        });

        $matcher->match('remainingText')->willReturn($matchedText);
        $matcher->getName()->willReturn('matcher');

        $matchedText->getAll()->willReturn(['all' => 'remainingText']);

        $this->findMatchingToken($lexer, $cursor)->shouldReturnAnInstanceOf(TokenInterface::class);
    }
}
