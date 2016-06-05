<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use PhpSpec\ObjectBehavior;

class AmbiguousTokenFoundExceptionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            'stateName',
            'text',
            ['calledMatcher1', 'calledMatcher2'],
            ['MatchedToken1', 'MatchedToken2']
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\State\AmbiguousTokenFoundException');
    }

    public function it_is_an_exception()
    {
        $this->shouldHaveType(\RuntimeException::class);
    }
}
