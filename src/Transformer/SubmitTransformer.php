<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;
use Symfony\Component\Form\FormInterface;

class SubmitTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, array $extensions = [], $widget = null): array
    {
        $schema = ['type' => 'submit'];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}
