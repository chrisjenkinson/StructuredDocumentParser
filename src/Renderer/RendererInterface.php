<?php

declare(strict_types=1);

namespace chrisjenkinson\StructuredDocumentParser\Renderer;

use chrisjenkinson\StructuredDocumentParser\Visitor\VisitorInterface;

/**
 * A visitor that produces output, such as HTML, from a node tree.
 */
interface RendererInterface extends VisitorInterface
{
}
