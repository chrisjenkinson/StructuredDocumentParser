<?php

namespace chrisjenkinson\StructuredDocumentParser\Visitor;

interface VisitableInterface
{
    public function accept(VisitorInterface $visitor);
}
