<?php

namespace chrisjenkinson\StructuredDocumentParser\Visitor;

interface Visitable
{
    public function accept(Visitor $visitor);
}