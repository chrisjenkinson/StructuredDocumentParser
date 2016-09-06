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

    public function it_has_the_state_name()
    {
        $this->getStateName()->shouldReturn('stateName');
    }

    public function it_has_the_text()
    {
        $this->getText()->shouldReturn('text');
    }

    public function it_has_the_called_matchers()
    {
        $this->getCalledMatchers()->shouldReturn(['calledMatcher1', 'calledMatcher2']);
    }

    public function it_has_the_matched_tokens()
    {
        $this->getMatchedTokens()->shouldReturn(['MatchedToken1', 'MatchedToken2']);
    }
}
