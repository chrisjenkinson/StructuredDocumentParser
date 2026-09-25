<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\Finder\RegexFinder;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Lexer\NoPreviousStateException;
use chrisjenkinson\StructuredDocumentParser\Lexer\ZeroLengthTokenLoopException;
use chrisjenkinson\StructuredDocumentParser\Matcher\AbstractMatcher;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\Matcher\SimpleTextMatcher;
use chrisjenkinson\StructuredDocumentParser\State\InitialState;
use chrisjenkinson\StructuredDocumentParser\State\NoTokenFoundException;
use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

class LexerSpec extends ObjectBehavior
{
    public function let(StateInterface $state): void
    {
        $this->beConstructedWith($state);
    }

    public function it_has_a_state(): void
    {
        $this->getState()->shouldBeAnInstanceOf(StateInterface::class);
    }

    public function it_can_tokenise(): void
    {
        $state = new InitialState();
        $state->registerMatcher(new SimpleTextMatcher());

        $this->beConstructedWith($state);

        $this->tokenise('1234')->shouldReturnAnInstanceOf(TokenStream::class);
    }

    public function it_can_switch_state(): void
    {
        $origState = new InitialState();
        $origState->registerMatcher(new SimpleTextMatcher());

        $newState = new InitialState();
        $newState->registerMatcher(new SimpleTextMatcher());

        $this->beConstructedWith($origState);

        $this->getState()->shouldReturn($origState);

        $this->pushState($newState);

        $this->getState()->shouldReturn($newState);
    }

    public function it_records_previous_states(): void
    {
        $origState = new InitialState();
        $origState->registerMatcher(new SimpleTextMatcher());

        $newState = new InitialState();
        $newState->registerMatcher(new SimpleTextMatcher());

        $this->beConstructedWith($origState);

        $this->pushState($newState);

        $this->getLastState()->shouldReturn($origState);
    }

    public function it_keeps_set_state_as_an_alias_for_push_state(): void
    {
        $origState = new InitialState();
        $newState  = new InitialState();

        $this->beConstructedWith($origState);

        $this->setState($newState);

        $this->getState()->shouldReturn($newState);
        $this->getLastState()->shouldReturn($origState);
    }

    public function it_throws_if_there_is_no_previous_state(): void
    {
        $this->shouldThrow(NoPreviousStateException::class)->during('getLastState');
    }

    public function it_pops_back_to_the_previous_state(): void
    {
        $firstState  = new InitialState();
        $secondState = new InitialState();
        $thirdState  = new InitialState();

        $this->beConstructedWith($firstState);

        $this->pushState($secondState);
        $this->pushState($thirdState);

        $this->popState();

        $this->getState()->shouldReturn($secondState);
        $this->getLastState()->shouldReturn($firstState);

        $this->popState();

        $this->getState()->shouldReturn($firstState);
        $this->shouldThrow(NoPreviousStateException::class)->during('getLastState');
    }

    public function it_throws_when_popping_without_a_previous_state(): void
    {
        $this->shouldThrow(NoPreviousStateException::class)->during('popState');
    }

    public function it_gives_the_same_tokens_when_tokenising_the_same_text_twice(): void
    {
        $origState = new InitialState();
        $newState  = new InitialState();

        $origState->registerMatcher(new LexerSpecRegexMatcher('Letter', '/(?<all>a)/A'), static function (Lexer $lexer) use ($newState): void {
            $lexer->pushState($newState);
        });
        $newState->registerMatcher(new LexerSpecRegexMatcher('Other', '/(?<all>a)/A'));

        $this->beConstructedWith($origState);

        $this->tokenise('a')->__toString()->shouldReturn('Letter (a)');
        $this->tokenise('a')->__toString()->shouldReturn('Letter (a)');
    }

    public function it_restores_the_initial_state_after_tokenising(): void
    {
        $origState = new InitialState();
        $newState  = new InitialState();

        $origState->registerMatcher(new LexerSpecRegexMatcher('Letter', '/(?<all>a)/A'), static function (Lexer $lexer) use ($newState): void {
            $lexer->pushState($newState);
        });

        $this->beConstructedWith($origState);

        $this->shouldThrow(NoTokenFoundException::class)->during('tokenise', ['ab']);

        $this->getState()->shouldReturn($origState);
        $this->shouldThrow(NoPreviousStateException::class)->during('getLastState');
    }

    public function it_gives_each_token_its_position(): void
    {
        $state = new InitialState();
        $state->registerMatcher(new LexerSpecRegexMatcher('Line', '/(?<all>[^\n]*\n|[^\n]+)/A'));

        $this->beConstructedWith($state);

        $tokens = $this->tokenise("ab\ncd");

        $tokens->consumeToken()->getPosition()->shouldBeLike(new TokenPosition(1, 1));
        $tokens->consumeToken()->getPosition()->shouldBeLike(new TokenPosition(2, 1));
    }

    public function it_can_start_from_a_given_position(): void
    {
        $state = new InitialState();
        $state->registerMatcher(new LexerSpecRegexMatcher('Line', '/(?<all>[^\n]*\n|[^\n]+)/A'));

        $this->beConstructedWith($state);

        $tokens = $this->tokenise("ab\ncd", new TokenPosition(10, 5));

        $tokens->consumeToken()->getPosition()->shouldBeLike(new TokenPosition(10, 5));
        $tokens->consumeToken()->getPosition()->shouldBeLike(new TokenPosition(11, 1));
    }

    public function it_reports_errors_relative_to_the_given_position(): void
    {
        $state = new InitialState();

        $this->beConstructedWith($state);

        try {
            $this->getWrappedObject()->tokenise('ab', new TokenPosition(10, 5));
        } catch (NoTokenFoundException $exception) {
            if (10 !== $exception->getPosition()->getLine() || 5 !== $exception->getPosition()->getColumn()) {
                throw new FailureException('Expected the error at line 10, column 5.');
            }

            return;
        }

        throw new FailureException('Expected a NoTokenFoundException.');
    }

    public function it_switches_state_on_a_zero_length_lookahead_match(): void
    {
        $origState = new InitialState();
        $newState  = new InitialState();

        $origState->registerMatcher(new LexerSpecRegexMatcher('Letter', '/(?<all>a)/A'));
        $origState->registerMatcher(new LexerSpecRegexMatcher('Lookahead', '/(?<all>)(?=b)/A'), static function (Lexer $lexer) use ($newState): void {
            $lexer->pushState($newState);
        });
        $newState->registerMatcher(new LexerSpecRegexMatcher('Rest', '/(?<all>b+)/A'));

        $this->beConstructedWith($origState);

        $this->tokenise('abb')->__toString()->shouldReturn("Letter (a)\nLookahead ()\nRest (bb)");
    }

    public function it_throws_if_a_zero_length_match_does_not_switch_state(): void
    {
        $state = new InitialState();
        $state->registerMatcher(new LexerSpecRegexMatcher('Empty', '/(?<all>)/A'));

        $this->beConstructedWith($state);

        $this->shouldThrow(new ZeroLengthTokenLoopException('InitialState', 0))->during('tokenise', ['a']);
    }

    public function it_throws_if_zero_length_matches_return_to_a_state_without_advancing(): void
    {
        $firstState  = new InitialState();
        $secondState = new InitialState();

        $firstState->registerMatcher(new LexerSpecRegexMatcher('ToSecond', '/(?<all>)/A'), static function (Lexer $lexer) use ($secondState): void {
            $lexer->pushState($secondState);
        });
        $secondState->registerMatcher(new LexerSpecRegexMatcher('ToFirst', '/(?<all>)/A'), static function (Lexer $lexer) use ($firstState): void {
            $lexer->pushState($firstState);
        });

        $this->beConstructedWith($firstState);

        $this->shouldThrow(new ZeroLengthTokenLoopException('InitialState', 0))->during('tokenise', ['a']);
    }
}

class LexerSpecRegexMatcher extends AbstractMatcher
{
    private RegexFinder $finder;

    public function __construct(private string $type, string $pattern)
    {
        $this->finder = new RegexFinder($pattern);
    }

    public function match(string $text): ?MatchedText
    {
        if ($this->finder->find($text)) {
            return new MatchedText($this->finder->getMatches(['all']));
        }

        return null;
    }

    public function getName(): string
    {
        return $this->type . 'Matcher';
    }
}
