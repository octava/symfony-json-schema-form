<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;

use Symfony\Component\Form\FormInterface;

class ButtonTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, array $extensions = [], $widget = null): array
    {
        return [];
    }
}
