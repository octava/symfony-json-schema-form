<?php

namespace Octava\SymfonyJsonSchemaForm;

use Octava\SymfonyJsonSchemaForm\Transformer\ExtensionInterface;
use Symfony\Component\Form\FormInterface;

class SJSForm implements SJSFormInterface
{
    private ResolverInterface $resolver;

    /**
     * @var ExtensionInterface[]
     */
    private array $extensions = [];

    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function transform(FormInterface $form): array
    {
        $transformerData = $this->resolver->resolve($form);

        return $transformerData['transformer']->transform($form, $this->extensions, $transformerData['widget']);
    }

    public function addExtension(ExtensionInterface $extension): SJSFormInterface
    {
        $this->extensions[] = $extension;

        return $this;
    }
}
