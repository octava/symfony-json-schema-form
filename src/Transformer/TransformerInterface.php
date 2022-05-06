<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;

use Symfony\Component\Form\FormInterface;

interface TransformerInterface
{
    public function transform(FormInterface $form, array $extensions = [], $widget = null): array;
}
