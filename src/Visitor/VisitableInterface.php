<?php

namespace chrisjenkinson\StructuredDocumentParser\Visitor;

interface VisitableInterface
{
    /**
     * @param VisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(VisitorInterface $visitor);
}
