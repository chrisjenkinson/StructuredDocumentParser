<?php

declare(strict_types=1);

namespace Test\Contract;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class TypedPropertiesTest extends TestCase
{
    public function testEveryPropertyHasANativeType(): void
    {
        $untyped = [];

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../../src'));

        foreach ($files as $file) {
            if ('php' !== $file->getExtension()) {
                continue;
            }

            $class = 'chrisjenkinson\\StructuredDocumentParser\\'.str_replace(
                ['/', '.php'],
                ['\\', ''],
                substr($file->getRealPath(), strlen((string) realpath(__DIR__.'/../../src')) + 1)
            );

            foreach ((new ReflectionClass($class))->getProperties() as $property) {
                if ($property->getDeclaringClass()->getName() === $class && null === $property->getType()) {
                    $untyped[] = $class.'::$'.$property->getName();
                }
            }
        }

        sort($untyped);

        Assert::assertSame([], $untyped);
    }
}
