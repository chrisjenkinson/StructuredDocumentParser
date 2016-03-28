<?php

namespace chrisjenkinson\StructuredDocumentParser\Visitor;

interface VisitableInterface
{
    /**
     * @param $visitor
     *
     * @return mixed
     */
    public function accept($visitor);
}
