<?php

use Octava\SymfonyJsonSchemaForm\Transformer\AbstractTransformer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services()
        ->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services
        ->instanceof(\Octava\SymfonyJsonSchemaForm\Form\Extension\AddSymfonyJsonSchemaFormExtension::class)
        ->tag(
            'form.type_extension',
            [
            'extended-type' => \Symfony\Component\Form\Extension\Core\Type\FormType::class,
            ]
        );

    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Form\Extension\AddSymfonyJsonSchemaFormExtension::class)
        ->public();

    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Serializer\Normalizer\FormErrorNormalizer::class)
        ->arg('service', 'translator')
        ->tag('serializer.normalizer', ['priority' => -10]);
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Serializer\Normalizer\FormErrorNormalizer::class)
        ->public();
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Serializer\Normalizer\InitialValuesNormalizer::class)
        ->tag('serializer.normalizer', ['priority' => -10]);
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Resolver::class);
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\SJSForm::class);

    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Guesser\ValidatorGuesser::class)
        ->arg('class', 'validator.mapping.class_metadata_factory');

    $services
        ->set(AbstractTransformer::class)
        ->abstract()
        ->args(
            [
            'translator',
            \Octava\SymfonyJsonSchemaForm\Guesser\ValidatorGuesser::class,
            ]
        );

    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Transformer\IntegerTransformer::class)
        ->parent(AbstractTransformer::class)
        ->tag('sjsform.transformer', ['form_type' => 'integer']);
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Transformer\ArrayTransformer::class)
        ->parent(AbstractTransformer::class)
        ->arg('resolver', \Octava\SymfonyJsonSchemaForm\Resolver::class)
        ->tag('sjsform.transformer', ['form_type' => 'collection']);
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Transformer\CompoundTransformer::class)
        ->parent(AbstractTransformer::class)
        ->arg('resolver', \Octava\SymfonyJsonSchemaForm\Resolver::class)
        ->tag('sjsform.transformer', ['form_type' => 'compound']);
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Transformer\ChoiceTransformer::class)
        ->parent(AbstractTransformer::class)
        ->tag('sjsform.transformer', ['form_type' => 'choice']);
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Transformer\StringTransformer::class)
        ->parent(AbstractTransformer::class)
        ->tag('sjsform.transformer', ['form_type' => 'text'])
        ->tag('sjsform.transformer', ['form_type' => 'url', 'widget' => 'url'])
        ->tag('sjsform.transformer', ['form_type' => 'search', 'widget' => 'search'])
        ->tag('sjsform.transformer', ['form_type' => 'money', 'widget' => 'money'])
        ->tag('sjsform.transformer', ['form_type' => 'password', 'widget' => 'password'])
        ->tag('sjsform.transformer', ['form_type' => 'textarea', 'widget' => 'textarea'])
        ->tag('sjsform.transformer', ['form_type' => 'time', 'widget' => 'time'])
        ->tag('sjsform.transformer', ['form_type' => 'percent', 'widget' => 'percent'])
        ->tag('sjsform.transformer', ['form_type' => 'email', 'widget' => 'email']);
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Transformer\NumberTransformer::class)
        ->parent(AbstractTransformer::class)
        ->tag('sjsform.transformer', ['form_type' => 'number']);
    $services
        ->set(\Octava\SymfonyJsonSchemaForm\Transformer\BooleanTransformer::class)
        ->parent(AbstractTransformer::class)
        ->tag('sjsform.transformer', ['form_type' => 'checkbox', 'widget' => 'checkbox']);
};
