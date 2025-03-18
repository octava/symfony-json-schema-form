<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;

use Octava\SymfonyJsonSchemaForm\ResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CompoundTransformer extends AbstractTransformer
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
        $data = [];
        $order = 1;
        $required = [];

        foreach ($form->all() as $name => $field) {
            $transformerData = $this->resolver->resolve($field);
            $transformedChild = $transformerData['transformer']->transform($field, $extensions,
                $transformerData['widget']);
            $transformedChild['propertyOrder'] = $order;
            $data[$name] = $transformedChild;
            ++$order;

            if ($transformerData['transformer']->isRequired($field)) {
                $required[] = $field->getName();
            }
        }

        $schema = [
            'title' => $form->getConfig()->getOption('label'),
            'type' => 'object',
            'properties' => $data,
        ];

        if (!empty($required)) {
            $schema['required'] = $required;
        }

        $innerType = $form->getConfig()->getType()->getInnerType();

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        if (method_exists($innerType, 'buildSJSForm')) {
            $schema = $innerType->buildSJSForm($form, $schema);
        }

        return $schema;
    }
}
