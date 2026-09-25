<?php

declare(strict_types=1);

namespace Test\Contract;

use chrisjenkinson\StructuredDocumentParser\State\AbstractState;
use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class StateApiTest extends TestCase
{
    public function testStateInterfaceOnlyDeclaresWhatTheLexerUses(): void
    {
        Assert::assertSame(['findMatchingToken', 'getName'], $this->publicMethods(StateInterface::class));
    }

    public function testAbstractStateOnlyExposesItsPublicApi(): void
    {
        Assert::assertSame(['findMatchingToken', 'getName', 'registerMatcher'], $this->publicMethods(AbstractState::class));
    }

    /**
     * @param class-string $class
     *
     * @return string[]
     */
    private function publicMethods(string $class): array
    {
        $names = array_map(
            fn (ReflectionMethod $method): string => $method->getName(),
            (new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC)
        );

        sort($names);

        return $names;
    }
}
