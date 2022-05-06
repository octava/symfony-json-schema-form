<?php

namespace Octava\SymfonyJsonSchemaForm\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSymfonyJsonSchemaFormExtension extends AbstractTypeExtension
{
    public const OPTION = 'sjsform';

    public function getExtendedType(): string
    {
        return FormType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined([self::OPTION]);
    }
}
