<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Finder;

use PhpSpec\ObjectBehavior;

class RegexFinderSpec extends ObjectBehavior
{
    public function it_finds_text(): void
    {
        $this->beConstructedWith('/(.*)/Ax');

        $this->find('Something')->shouldReturn(true);
    }

    public function it_returns_false_if_nothing_is_found(): void
    {
        $this->beConstructedWith('/abc/');

        $this->find('def')->shouldReturn(false);
    }

    public function it_stores_matches(): void
    {
        $this->beConstructedWith('/(?<match1>.*)/Ax');

        $this->find('Something');

        $this->getMatches(['match1'])->shouldReturn(['match1' => 'Something']);
    }
}
