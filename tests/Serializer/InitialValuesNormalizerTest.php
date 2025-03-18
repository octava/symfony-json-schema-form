<?php

namespace Octava\SymfonyJsonSchemaForm\Tests\Serializer;

use Octava\SymfonyJsonSchemaForm\Serializer\Normalizer\InitialValuesNormalizer;
use Octava\SymfonyJsonSchemaForm\Tests\SymfonyJsonSchemaFormTestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InitialValuesNormalizerTest extends SymfonyJsonSchemaFormTestCase
{
    public function testConstruct(): void
    {
        $normalizer = new InitialValuesNormalizer();
        $this->assertInstanceOf(InitialValuesNormalizer::class, $normalizer);
    }

    public function testSimpleCase(): void
    {
        $form = $this->factory->create(FormType::class, ['firstName' => 'Joe'])
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class);
        $normalizer = new InitialValuesNormalizer();
        $data = (array) $normalizer->normalize($form);
        $this->assertEquals('Joe', $data['firstName']);
    }

    public function testChoiceExpandedMultiple(): void
    {
        $form = $this->factory->create(FormType::class, ['firstName' => ['A']])
            ->add(
                'firstName',
                ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'expanded' => true,
                    'multiple' => true,
                ]
            );

        $normalizer = new InitialValuesNormalizer();
        $data = (array) $normalizer->normalize($form);
        $this->assertEquals(['A'], $data['firstName']);
    }

    public function testChoiceExpanded(): void
    {
        $form = $this->factory->create(FormType::class, ['firstName' => 'A'])
            ->add(
                'firstName',
                ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'expanded' => true,
                ]
            );

        $normalizer = new InitialValuesNormalizer();
        $data = (array) $normalizer->normalize($form);
        $this->assertEquals('A', $data['firstName']);
    }
}
