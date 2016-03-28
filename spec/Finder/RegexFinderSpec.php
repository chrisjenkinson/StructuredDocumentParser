<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\Finder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegexFinderSpec extends ObjectBehavior
{
    public function it_finds_text()
    {
        $this->beConstructedWith('/(.*)/Ax');

        $this->find('Something')->shouldReturn(true);
    }

    public function it_returns_false_if_nothing_is_found()
    {
        $this->beConstructedWith('/abc/');

        $this->find('def')->shouldReturn(false);
    }

    public function it_stores_matches()
    {
        $this->beConstructedWith('/(?<match1>.*)/Ax');

        $this->find('Something');

        $this->getMatches(['match1'])->shouldReturn(['match1' => 'Something']);
    }
}
