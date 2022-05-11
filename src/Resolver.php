<?php

namespace Octava\SymfonyJsonSchemaForm;

use Octava\SymfonyJsonSchemaForm\Exception\TransformerException;
use Octava\SymfonyJsonSchemaForm\Transformer\TransformerInterface;
use Symfony\Component\Form\FormInterface;

class Resolver implements ResolverInterface
{
    private array $transformers = [];

    public function setTransformer($formType, TransformerInterface $transformer, $widget = null): void
    {
        $this->transformers[$formType] = [
            'transformer' => $transformer,
            'widget' => $widget,
        ];
    }

    public function resolve(FormInterface $form): ?array
    {
        $types = FormUtil::typeAncestry($form);

        foreach ($types as $type) {
            if (isset($this->transformers[$type])) {
                return $this->transformers[$type];
            }
        }

        // Perhaps a compound we don't have a specific transformer for
        if (FormUtil::isCompound($form)) {
            return [
                'transformer' => $this->transformers['compound']['transformer'],
                'widget' => null,
            ];
        }

        return $this->transformers['default'];
    }
}
