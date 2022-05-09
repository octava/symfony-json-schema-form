<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;

use Octava\SymfonyJsonSchemaForm\Form\Extension\AddSymfonyJsonSchemaFormExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractTransformer implements TransformerInterface
{
    protected TranslatorInterface $translator;

    protected ?FormTypeGuesserInterface $validatorGuesser = null;

    public function __construct(TranslatorInterface $translator, ?FormTypeGuesserInterface $validatorGuesser = null)
    {
        $this->translator = $translator;
        $this->validatorGuesser = $validatorGuesser;
    }

    public function isRequired(FormInterface $form): bool
    {
        return $form->getConfig()->getOption('required');
    }

    protected function applyExtensions(array $extensions, FormInterface $form, array $schema): array
    {
        $newSchema = $schema;
        foreach ($extensions as $extension) {
            $newSchema = $extension->apply($form, $newSchema);
        }

        return $newSchema;
    }

    protected function addCommonSpecs(FormInterface $form, array $schema, $extensions = [], $widget = null): array
    {
        $schema = $this->addLabel($form, $schema);
        $schema = $this->addAttr($form, $schema);
        $schema = $this->addPattern($form, $schema);
        $schema = $this->addDescription($form, $schema);
        $schema = $this->addWidget($form, $schema, $widget);
        $schema = $this->applyExtensions($extensions, $form, $schema);

        return $schema;
    }

    protected function addPattern(FormInterface $form, array $schema): array
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['pattern'])) {
                $schema['pattern'] = $attr['pattern'];
            }
        }

        return $schema;
    }

    protected function addLabel(FormInterface $form, array $schema): array
    {
        $translationDomain = $form->getConfig()->getOption('translation_domain');
        if ($label = $form->getConfig()->getOption('label')) {
            $schema['title'] = $this->translator->trans($label, [], $translationDomain);
        } else {
            $schema['title'] = $this->translator->trans($form->getName(), [], $translationDomain);
        }

        return $schema;
    }

    protected function addAttr(FormInterface $form, array $schema): array
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            $schema['attr'] = $attr;
        }

        return $schema;
    }

    protected function addDescription(FormInterface $form, array $schema): array
    {
        $formConfig = $form->getConfig();
        if ($help = $formConfig->getOption('help', '')) {
            $schema['description'] = $this->translator->trans($help);
        }

        if ($sjsform = $formConfig->getOption('sjsform')) {
            if (isset($sjsform['description']) && $description = $sjsform['description']) {
                $schema['description'] = $this->translator->trans($description);
            }
        }

        return $schema;
    }

    protected function addWidget(FormInterface $form, array $schema, $configWidget): array
    {
        if ($sjsform = $form->getConfig()->getOption(AddSymfonyJsonSchemaFormExtension::OPTION)) {
            if (isset($sjsform['widget']) && $widget = $sjsform['widget']) {
                $schema['widget'] = $widget;
            }
        } elseif ($configWidget) {
            $schema['widget'] = $configWidget;
        }

        return $schema;
    }
}
