# Structured Document Parser

A PHP library for turning semi-structured text into a tree you can work with. It gives you the pieces for each stage and leaves the grammar to you:

- **Lexing.** A `Lexer` runs your matchers against the text and produces a `TokenStream`. Matchers are grouped into states, and a matcher's callback can push or pop states. Lookahead matches that consume nothing are allowed when they switch state. Each token records its line and column.
- **Parsing.** You implement `ParserInterface` to turn tokens into a tree of your own node classes, built on `AbstractNode`. A node holds attributes, and a series of child nodes (such as a document's blocks) goes in an array attribute.
- **Transforming.** A `NodeTraverser` walks the tree and calls your `NodeVisitorInterface` visitors, which can replace or remove nodes.
- **Rendering.** `VisitorInterface`, `VisitableInterface` and `RendererInterface` are extension points for double-dispatch visitors, for example a renderer with a `visitHeading()` method per node type.

## Requirements

PHP 8.2 or later with the `mbstring` extension.

## Installation

The package isn't on Packagist yet, so add the repository to your `composer.json`:

```json
{
    "repositories": [
        { "type": "vcs", "url": "https://github.com/chrisjenkinson/StructuredDocumentParser" }
    ],
    "require": {
        "chrisjenkinson/structured-document-parser": "dev-master"
    }
}
```

## Usage

This example parses a small Markdown-like document with headings and paragraphs, then gives each heading an id.

```php
require __DIR__ . '/vendor/autoload.php';

use chrisjenkinson\StructuredDocumentParser\Finder\RegexFinder;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Matcher\AbstractMatcher;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
use chrisjenkinson\StructuredDocumentParser\Node\AbstractNode;
use chrisjenkinson\StructuredDocumentParser\Node\NodeInterface;
use chrisjenkinson\StructuredDocumentParser\NodeTraverser\NodeTraverser;
use chrisjenkinson\StructuredDocumentParser\NodeVisitor\AbstractNodeVisitor;
use chrisjenkinson\StructuredDocumentParser\Parser\ParserInterface;
use chrisjenkinson\StructuredDocumentParser\State\InitialState;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;

// 1. Matchers recognise pieces of text. The token type is the matcher name without "Matcher".
final class RegexMatcher extends AbstractMatcher
{
    private readonly RegexFinder $finder;

    public function __construct(private readonly string $type, string $pattern)
    {
        $this->finder = new RegexFinder($pattern);
    }

    public function getName(): string
    {
        return $this->type . 'Matcher';
    }

    public function match(string $text): ?MatchedText
    {
        if (!$this->finder->find($text)) {
            return null;
        }

        return new MatchedText($this->finder->getMatches(['all', 'level', 'text']));
    }
}

// 2. A state holds the matchers that apply at a point in the document.
$state = new InitialState();
$state->registerMatcher(new RegexMatcher('Heading', '/(?<all>(?<level>#{1,6}) (?<text>[^\n]*)\n?)/A'));
$state->registerMatcher(new RegexMatcher('Paragraph', '/(?<all>(?<text>[^#\n][^\n]*)\n?)/A'));
$state->registerMatcher(new RegexMatcher('BlankLine', '/(?<all>\n)/A'));

$tokens = (new Lexer($state))->tokenise("# Title\nSome text.\n\n## Section\nMore text.\n");

echo $tokens, "\n\n";

// 3. Your own node classes and parser turn tokens into a tree.
final class Document extends AbstractNode {}
final class Heading extends AbstractNode {}
final class Paragraph extends AbstractNode {}

final class DocumentParser implements ParserInterface
{
    public function parse(TokenStream $tokens): NodeInterface
    {
        $blocks = [];

        while (null !== $token = $tokens->getCurrentToken()) {
            $tokens->consumeToken();

            $block = match ($token->getType()) {
                'Heading'   => new Heading(),
                'Paragraph' => new Paragraph(),
                default     => null,
            };

            if (null === $block) {
                continue;
            }

            $block->setAttribute('text', $token->getValue('text'));

            if ($token->hasKey('level')) {
                $block->setAttribute('level', mb_strlen($token->getValue('level')));
            }

            $blocks[] = $block;
        }

        $document = new Document();
        $document->setAttribute('blocks', $blocks);

        return $document;
    }
}

$document = (new DocumentParser())->parse($tokens);

// 4. Visitors transform the tree: here, giving each heading an id.
final class HeadingIdVisitor extends AbstractNodeVisitor
{
    public function enterNode(NodeInterface $node): ?NodeInterface
    {
        if ($node instanceof Heading) {
            $node->setAttribute('id', mb_strtolower(str_replace(' ', '-', $node->getAttribute('text'))));
        }

        return null;
    }
}

$traverser = new NodeTraverser();
$traverser->addVisitor(new HeadingIdVisitor());

echo $traverser->traverse($document), "\n";
```

The lexer produces these tokens:

```
Heading (# Title)
Paragraph (Some text.)
BlankLine ()
Heading (## Section)
Paragraph (More text.)
```

and the traversed document contains the headings with their ids:

```json
{
    "attributes": {
        "blocks": [
            {
                "attributes": {
                    "text": "Title",
                    "level": 1,
                    "id": "title"
                },
                "nodes": []
            },
            ...
        ]
    },
    "nodes": []
}
```

If no matcher matches, or more than one does, the lexer throws `NoTokenFoundException` or `AmbiguousTokenFoundException`, with the line, column and the text at that point.

## Development

```sh
composer install
composer test     # phpspec, PHPUnit, PHPStan and the code style check
composer cs-fix   # fix code style
```

## Licence

See [LICENSE](LICENSE).
