<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;

use Octava\SymfonyJsonSchemaForm\Exception\TransformerException;
use Octava\SymfonyJsonSchemaForm\ResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArrayTransformer extends AbstractTransformer
{
    protected ResolverInterface $resolver;

    public function __construct(
        TranslatorInterface $translator,
        ?FormTypeGuesserInterface $validatorGuesser = null,
        ?ResolverInterface $resolver = null,
    ) {
        parent::__construct($translator, $validatorGuesser);
        $this->resolver = $resolver;
    }

    public function transform(FormInterface $form, array $extensions = [], $widget = null): array
    {
        $children = [];

        foreach ($form->all() as $name => $field) {
            $transformerData = $this->resolver->resolve($field);
            $transformedChild = $transformerData['transformer']->transform(
                $field,
                $extensions,
                $transformerData['widget']
            );
            $children[] = $transformedChild;

            if ($transformerData['transformer']->isRequired($field)) {
                $required[] = $field->getName();
            }
        }

        if (empty($children)) {
            $entryType = $form->getConfig()->getAttribute('prototype');

            if (!$entryType) {
                throw new TransformerException(sprintf('Liform cannot infer the json-schema representation of a an empty Collection or array-like type without the option "allow_add" (to check the proptotype). Evaluating "%s', $form->getName()));
            }

            $transformerData = $this->resolver->resolve($entryType);
            $children[] = $transformerData['transformer']->transform($entryType, $extensions,
                $transformerData['widget']);
            $children[0]['title'] = 'prototype';
        }

        $schema = [
            'type' => 'array',
            'title' => $form->getConfig()->getOption('label'),
            'items' => $children[0],
        ];

        return $this->addCommonSpecs($form, $schema, $extensions, $widget);
    }
}
