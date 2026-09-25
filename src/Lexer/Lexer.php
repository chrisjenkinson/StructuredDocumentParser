<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;

class Lexer
{
    private StateInterface $state;

    /**
     * @var StateInterface[]
     */
    private array $previousStates = [];

    public function __construct(private readonly StateInterface $initialState)
    {
        $this->state = $initialState;
    }

    public function tokenise(string $text): TokenStream
    {
        $this->reset();

        try {
            return $this->tokeniseFromInitialState($text);
        } finally {
            $this->reset();
        }
    }

    public function getState(): StateInterface
    {
        return $this->state;
    }

    /**
     * Switches to the given state, recording the current one so popState() can return to it.
     *
     * States are compared by identity when detecting zero-length token loops, so reuse
     * state instances rather than creating new ones on each switch.
     */
    public function pushState(StateInterface $state): void
    {
        $this->previousStates[] = $this->state;
        $this->state            = $state;
    }

    /**
     * @deprecated use pushState()
     */
    public function setState(StateInterface $state): void
    {
        $this->pushState($state);
    }

    public function getLastState(): StateInterface
    {
        if ([] === $this->previousStates) {
            throw new NoPreviousStateException();
        }

        return end($this->previousStates);
    }

    public function popState(): void
    {
        $this->state = $this->getLastState();

        array_pop($this->previousStates);
    }

    private function tokeniseFromInitialState(string $text): TokenStream
    {
        $tokens = new TokenStream();
        $cursor = new Cursor($text);

        $statesAtPosition = [];

        while (!$cursor->isEndOfText()) {
            $state = $this->getState();

            if (in_array($state, $statesAtPosition, true)) {
                throw new ZeroLengthTokenLoopException($state->getName(), $cursor->getCurrentPosition());
            }

            $statesAtPosition[] = $state;

            $token = $state->findMatchingToken($this, $cursor);

            $tokens->add($token);

            $all = $token->getValue('all');
            assert(is_string($all));

            $length = mb_strlen($all);

            if (0 < $length) {
                $cursor->advance($length);
                $statesAtPosition = [];
            }
        }

        return $tokens;
    }

    private function reset(): void
    {
        $this->state          = $this->initialState;
        $this->previousStates = [];
    }
}
