<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;

class Lexer
{
    /**
     * @var StateInterface
     */
    private $state;

    /**
     * @var StateInterface[]
     */
    private $previousStates = [];

    public function __construct(StateInterface $initialState)
    {
        $this->state = $initialState;
    }

    public function tokenise(string $text): TokenStream
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

            $length = mb_strlen($token->getValue('all'));

            if (0 < $length) {
                $cursor->advance($length);
                $statesAtPosition = [];
            }
        }

        return $tokens;
    }

    public function getState(): StateInterface
    {
        return $this->state;
    }

    public function setState(StateInterface $state): void
    {
        $this->previousStates[] = $this->state;
        $this->state            = $state;
    }

    public function getLastState(): StateInterface
    {
        return end($this->previousStates);
    }
}
