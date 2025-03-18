<?php

namespace Octava\SymfonyJsonSchemaForm\Tests\Transformer;

use Octava\SymfonyJsonSchemaForm\Resolver;
use Octava\SymfonyJsonSchemaForm\Tests\SymfonyJsonSchemaFormTestCase;
use Octava\SymfonyJsonSchemaForm\Transformer;
use Octava\SymfonyJsonSchemaForm\Transformer\CompoundTransformer;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class ChoiceTransformerTest extends SymfonyJsonSchemaFormTestCase
{
    public function testChoice(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                Type\ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                ]
            );

        // 4 times: firstName, form, and the two choices
        $this->translator->expects($this->exactly(4))
            ->method('trans')
            ->will(
                $this->returnCallback(
                    function ($str) {
                        return $str . '-translated';
                    }
                )
            );

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertArrayHasKey('enum_titles', $transformed['properties']['firstName']);
        $this->assertArrayHasKey('enum_titles', $transformed['properties']['firstName']['options']);
        $this->assertEquals(['a-translated', 'b-translated'], $transformed['properties']['firstName']['enum_titles']);
        $this->assertEquals(
            ['a-translated', 'b-translated'],
            $transformed['properties']['firstName']['options']['enum_titles']
        );
        $this->assertArrayHasKey('enum', $transformed['properties']['firstName']);
        $this->assertEquals(['A', 'B'], $transformed['properties']['firstName']['enum']);
    }

    public function testChoiceExpanded(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                Type\ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'expanded' => true,
                ]
            );

        // 4 times: firstName, form, and the two choices
        $this->translator->expects($this->exactly(4))
            ->method('trans')
            ->will(
                $this->returnCallback(
                    function ($str) {
                        return $str . '-translated';
                    }
                )
            );

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertArrayHasKey('enum_titles', $transformed['properties']['firstName']);
        $this->assertArrayHasKey('enum_titles', $transformed['properties']['firstName']['options']);
        $this->assertEquals(['a-translated', 'b-translated'], $transformed['properties']['firstName']['enum_titles']);
        $this->assertEquals(
            ['a-translated', 'b-translated'],
            $transformed['properties']['firstName']['options']['enum_titles']
        );
        $this->assertArrayHasKey('enum', $transformed['properties']['firstName']);
        $this->assertEquals(['A', 'B'], $transformed['properties']['firstName']['enum']);
        $this->assertArrayHasKey('widget', $transformed['properties']['firstName']);
        $this->assertEquals('choice-expanded', $transformed['properties']['firstName']['widget']);
    }

    public function testChoiceMultiple(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                Type\ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'multiple' => true,
                ]
            );

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertArrayHasKey('items', $transformed['properties']['firstName']);
        $this->assertEquals('array', $transformed['properties']['firstName']['type']);
        $this->assertArrayNotHasKey('widget', $transformed['properties']['firstName']);
    }

    public function testChoiceMultipleExpanded(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                Type\ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'expanded' => true,
                    'multiple' => true,
                ]
            );

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertArrayHasKey('items', $transformed['properties']['firstName']);
        $this->assertEquals('array', $transformed['properties']['firstName']['type']);
        $this->assertArrayHasKey('widget', $transformed['properties']['firstName']);
        $this->assertEquals('choice-multiple-expanded', $transformed['properties']['firstName']['widget']);
    }
}
