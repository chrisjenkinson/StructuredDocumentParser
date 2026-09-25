<?php

declare(strict_types=1);

namespace Test\Lexer;

use chrisjenkinson\StructuredDocumentParser\Finder\RegexFinder;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Lexer\ZeroLengthTokenLoopException;
use chrisjenkinson\StructuredDocumentParser\Matcher\AbstractMatcher;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\State\InitialState;
use chrisjenkinson\StructuredDocumentParser\State\NoTokenFoundException;
use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    public function testItSwitchesStateOnAZeroLengthLookaheadMatch(): void
    {
        $origState = new InitialState();
        $newState  = new InitialState();

        $origState->registerMatcher(new RegexMatcher('Letter', '/(?<all>a)/A'));
        $origState->registerMatcher(new RegexMatcher('Lookahead', '/(?<all>)(?=b)/A'), static function (Lexer $lexer) use ($newState): void {
            $lexer->pushState($newState);
        });
        $newState->registerMatcher(new RegexMatcher('Rest', '/(?<all>b+)/A'));

        Assert::assertSame("Letter (a)\nLookahead ()\nRest (bb)", (string) (new Lexer($origState))->tokenise('abb'));
    }

    public function testItThrowsIfAZeroLengthMatchDoesNotSwitchState(): void
    {
        $state = new InitialState();
        $state->registerMatcher(new RegexMatcher('Empty', '/(?<all>)/A'));

        $this->expectException(ZeroLengthTokenLoopException::class);

        (new Lexer($state))->tokenise('a');
    }

    public function testItThrowsIfZeroLengthMatchesReturnToAStateWithoutAdvancing(): void
    {
        $firstState  = new InitialState();
        $secondState = new InitialState();

        $firstState->registerMatcher(new RegexMatcher('ToSecond', '/(?<all>)/A'), static function (Lexer $lexer) use ($secondState): void {
            $lexer->pushState($secondState);
        });
        $secondState->registerMatcher(new RegexMatcher('ToFirst', '/(?<all>)/A'), static function (Lexer $lexer) use ($firstState): void {
            $lexer->pushState($firstState);
        });

        $this->expectException(ZeroLengthTokenLoopException::class);

        (new Lexer($firstState))->tokenise('a');
    }

    public function testItGivesTheSameTokensWhenTokenisingTheSameTextTwice(): void
    {
        $origState = new InitialState();
        $newState  = new InitialState();

        $origState->registerMatcher(new RegexMatcher('Letter', '/(?<all>a)/A'), static function (Lexer $lexer) use ($newState): void {
            $lexer->pushState($newState);
        });
        $newState->registerMatcher(new RegexMatcher('Other', '/(?<all>a)/A'));

        $lexer = new Lexer($origState);

        Assert::assertSame('Letter (a)', (string) $lexer->tokenise('a'));
        Assert::assertSame('Letter (a)', (string) $lexer->tokenise('a'));
    }

    public function testItGivesEachTokenItsPosition(): void
    {
        $tokens = (new Lexer($this->lineState()))->tokenise("ab\ncd");

        Assert::assertEquals(new TokenPosition(1, 1), $tokens->consumeToken()->getPosition());
        Assert::assertEquals(new TokenPosition(2, 1), $tokens->consumeToken()->getPosition());
    }

    public function testItGivesPositionsRelativeToAGivenStartPosition(): void
    {
        $tokens = (new Lexer($this->lineState()))->tokenise("ab\ncd", new TokenPosition(10, 5));

        Assert::assertEquals(new TokenPosition(10, 5), $tokens->consumeToken()->getPosition());
        Assert::assertEquals(new TokenPosition(11, 1), $tokens->consumeToken()->getPosition());
    }

    public function testItReportsErrorsRelativeToAGivenStartPosition(): void
    {
        try {
            (new Lexer(new InitialState()))->tokenise('ab', new TokenPosition(10, 5));
        } catch (NoTokenFoundException $exception) {
            Assert::assertEquals(new TokenPosition(10, 5), $exception->getPosition());

            return;
        }

        Assert::fail('Expected a NoTokenFoundException.');
    }

    private function lineState(): InitialState
    {
        $state = new InitialState();
        $state->registerMatcher(new RegexMatcher('Line', '/(?<all>[^\n]*\n|[^\n]+)/A'));

        return $state;
    }
}

class RegexMatcher extends AbstractMatcher
{
    private readonly RegexFinder $finder;

    public function __construct(private readonly string $type, string $pattern)
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
