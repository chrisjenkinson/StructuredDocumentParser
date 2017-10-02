<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Matcher;

use PhpSpec\ObjectBehavior;

class MatchedTextSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(['all' => '1234']);
    }

    public function it_can_get_individual_elements(): void
    {
        $this->get('all')->shouldReturn('1234');
    }

    public function it_returns_null_if_the_element_does_not_exist(): void
    {
        $this->get('nonexistent')->shouldReturn(null);
    }

    public function it_can_get_all_elements(): void
    {
        $this->getAll()->shouldReturn(['all' => '1234']);
    }
}
