<?php

namespace Octava\SymfonyJsonSchemaForm;

use Octava\SymfonyJsonSchemaForm\Transformer\TransformerInterface;
use Symfony\Component\Form\FormInterface;

interface ResolverInterface
{
    public function setTransformer($formType, TransformerInterface $transformer, $widget = null): void;

    public function resolve(FormInterface $form);
}
