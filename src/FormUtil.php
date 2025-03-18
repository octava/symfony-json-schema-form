<?php

namespace Octava\SymfonyJsonSchemaForm;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;

class FormUtil
{
    public static function typeAncestry(FormInterface $form): array
    {
        $types = [];
        self::typeAncestryForType($form->getConfig()->getType(), $types);

        return $types;
    }

    public static function typeAncestryForType(?ResolvedFormTypeInterface $formType = null, array &$types = []): void
    {
        if (!($formType instanceof ResolvedFormTypeInterface)) {
            return;
        }

        $types[] = $formType->getBlockPrefix();

        self::typeAncestryForType($formType->getParent(), $types);
    }

    public static function findDataClass($formType): ?string
    {
        if ($dataClass = $formType->getConfig()->getDataClass()) {
            return $dataClass;
        } else {
            if ($parent = $formType->getParent()) {
                return self::findDataClass($parent);
            } else {
                return null;
            }
        }
    }

    public static function isTypeInAncestry(FormInterface $form, $type): bool
    {
        return in_array($type, self::typeAncestry($form));
    }

    public static function type(FormInterface $form)
    {
        return $form->getConfig()->getType()->getName();
    }

    public static function label(FormInterface $form)
    {
        return $form->getConfig()->getOption('label', $form->getName());
    }

    public static function isCompound(FormInterface $form)
    {
        return $form->getConfig()->getOption('compound');
    }
}
