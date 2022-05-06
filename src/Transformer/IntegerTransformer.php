<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;

use Symfony\Component\Form\FormInterface;

class IntegerTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, array $extensions = [], $widget = null): array
    {
        $schema = ['type' => 'integer'];
        return $this->addCommonSpecs($form, $schema, $extensions, $widget);
    }
}
