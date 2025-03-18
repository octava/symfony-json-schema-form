<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;

use Symfony\Component\Form\FormInterface;

class DefaultTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, array $extensions = [], $widget = null): array
    {
        $schema = ['type' => 'string'];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);
        $schema = $this->addMaxLength($form, $schema);

        return $this->addMinLength($form, $schema);
    }
}
