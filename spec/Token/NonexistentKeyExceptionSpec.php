<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Token;

use chrisjenkinson\StructuredDocumentParser\Token\NonexistentKeyException;
use PhpSpec\ObjectBehavior;
use RuntimeException;

class NonexistentKeyExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(NonexistentKeyException::class);
    }

    public function it_is_an_exception(): void
    {
        $this->shouldHaveType(RuntimeException::class);
    }
}
