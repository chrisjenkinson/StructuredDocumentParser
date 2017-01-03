<?php

namespace chrisjenkinson\StructuredDocumentParser\Visitor;

/**
 * Interface VisitableInterface
 * @package chrisjenkinson\StructuredDocumentParser\Visitor
 */
interface VisitableInterface
{
    /**
     * @param $visitor
     *
     * @return mixed
     */
    public function accept($visitor);
}
