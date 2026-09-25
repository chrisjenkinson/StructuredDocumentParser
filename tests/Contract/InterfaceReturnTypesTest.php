<?php

declare(strict_types=1);

namespace Test\Contract;

use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\NodeVisitorInterface;
use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;
use chrisjenkinson\StructuredDocumentParser\Visitor\VisitableInterface;
use chrisjenkinson\StructuredDocumentParser\Visitor\VisitorInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class InterfaceReturnTypesTest extends TestCase
{
    /**
     * @return array<string, array{class-string, string, string}>
     */
    public static function methods(): array
    {
        return [
            'StateInterface::findMatchingToken' => [StateInterface::class, 'findMatchingToken', TokenInterface::class],
            'NodeInterface::getAttribute'       => [NodeInterface::class, 'getAttribute', 'mixed'],
            'NodeInterface::getAttributes'      => [NodeInterface::class, 'getAttributes', 'array'],
            'NodeInterface::removeNode'         => [NodeInterface::class, 'removeNode', 'void'],
            'NodeVisitorInterface::leaveNode'   => [NodeVisitorInterface::class, 'leaveNode', 'chrisjenkinson\StructuredDocumentParser\Node\NodeInterface|chrisjenkinson\StructuredDocumentParser\NodeVisitor\NodeVisitorAction|null'],
            'TokenInterface::getValues'         => [TokenInterface::class, 'getValues', 'array'],
            'TokenInterface::getValue'          => [TokenInterface::class, 'getValue', 'mixed'],
            'VisitableInterface::accept'        => [VisitableInterface::class, 'accept', 'mixed'],
        ];
    }

    #[DataProvider('methods')]
    public function testItDeclaresANativeReturnType(string $class, string $method, string $expectedType): void
    {
        Assert::assertSame($expectedType, (string) (new ReflectionMethod($class, $method))->getReturnType());
    }

    public function testVisitableAcceptsAVisitor(): void
    {
        $parameter = (new ReflectionMethod(VisitableInterface::class, 'accept'))->getParameters()[0];

        Assert::assertSame(VisitorInterface::class, (string) $parameter->getType());
    }
}
