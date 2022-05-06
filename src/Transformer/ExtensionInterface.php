<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;

use Symfony\Component\Form\FormInterface;

interface ExtensionInterface
{
    public function apply(FormInterface $form, array $schema): array;
}
