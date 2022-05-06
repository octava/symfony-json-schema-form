<?php

namespace Octava\SymfonyJsonSchemaForm;

use Octava\SymfonyJsonSchemaForm\Transformer\ExtensionInterface;
use Symfony\Component\Form\FormInterface;

interface SJSFormInterface
{
    public function transform(FormInterface $form): array;

    public function addExtension(ExtensionInterface $extension): SJSFormInterface;
}
