<?php

namespace Octava\SymfonyJsonSchemaForm\Serializer\Normalizer;

use Octava\SymfonyJsonSchemaForm\FormUtil;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class InitialValuesNormalizer implements NormalizerInterface
{
    public function normalize($form, $format = null, array $context = [])
    {
        $formView = $form->createView();

        return $this->getValues($form, $formView);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Form;
    }

    private function getValues(Form $form, FormView $formView)
    {
        if (!empty($formView->children)) {
            if (in_array('choice', FormUtil::typeAncestry($form)) &&
                $formView->vars['expanded']
            ) {
                if ($formView->vars['multiple']) {
                    return $this->normalizeMultipleExpandedChoice($formView);
                } else {
                    return $this->normalizeExpandedChoice($formView);
                }
            }
            // Force serialization as {} instead of []
            $data = (object)[];
            foreach ($formView->children as $name => $child) {
                // Avoid unknown field error when csrf_protection is true
                // CSRF token should be extracted another way
                if ($form->has($name)) {
                    $data->{$name} = $this->getValues($form->get($name), $child);
                }
            }

            return (array)$data;
        } else {
            // handle separatedly the case with checkboxes, so the result is
            // true/false instead of 1/0
            if (isset($formView->vars['checked'])) {
                return $formView->vars['checked'];
            }

            return $formView->vars['value'];
        }
    }

    private function normalizeMultipleExpandedChoice(FormView $formView): array
    {
        $data = [];
        foreach ($formView->children as $name => $child) {
            if ($child->vars['checked']) {
                $data[] = $child->vars['value'];
            }
        }

        return $data;
    }

    private function normalizeExpandedChoice(FormView $formView)
    {
        foreach ($formView->children as $child) {
            if ($child->vars['checked']) {
                return $child->vars['value'];
            }
        }

        return null;
    }
}
