<?php

namespace Octava\SymfonyJsonSchemaForm\Transformer;

use Octava\SymfonyJsonSchemaForm\FormUtil;
use Symfony\Component\Form\FormInterface;

class StringTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, array $extensions = [], $widget = null): array
    {
        $schema = ['type' => 'string'];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);
        $schema = $this->addMaxLength($form, $schema);

        return $this->addMinLength($form, $schema);
    }

    protected function addMaxLength(FormInterface $form, array $schema): array
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['maxlength'])) {
                $schema['maxLength'] = $attr['maxlength'];
            }
        }
        if (null === $this->validatorGuesser) {
            return $schema;
        }

        $class = FormUtil::findDataClass($form);

        if (null === $class) {
            return $schema;
        }

        $minLengthGuess = $this->validatorGuesser->guessMaxLength($class, $form->getName());
        $minLength = $minLengthGuess ? $minLengthGuess->getValue() : null;

        if ($minLength) {
            $schema['maxlength'] = $minLength;
        }

        return $schema;
    }

    protected function addMinLength(FormInterface $form, array $schema): array
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['minlength'])) {
                $schema['minLength'] = $attr['minlength'];

                return $schema;
            }
        }
        if (null === $this->validatorGuesser) {
            return $schema;
        }
        $class = FormUtil::findDataClass($form);

        if (null === $class) {
            return $schema;
        }

        $minLengthGuess = $this->validatorGuesser->guessMaxLength($class, $form->getName());
        $minLength = $minLengthGuess ? $minLengthGuess->getValue() : null;

        if ($minLength) {
            $schema['minLength'] = $minLength;
        }
        return $schema;
    }
}
