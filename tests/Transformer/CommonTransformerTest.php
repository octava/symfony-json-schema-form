<?php

namespace Octava\SymfonyJsonSchemaForm\Tests\Transformer;

use Octava\SymfonyJsonSchemaForm\Resolver;
use Octava\SymfonyJsonSchemaForm\Tests\SymfonyJsonSchemaFormTestCase;
use Octava\SymfonyJsonSchemaForm\Transformer\CompoundTransformer;
use Octava\SymfonyJsonSchemaForm\Transformer\StringTransformer;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommonTransformerTest extends SymfonyJsonSchemaFormTestCase
{
    public function testRequired(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['required' => true]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertArrayHasKey('required', $transformed);
        $this->assertTrue(is_array($transformed['required']));
        $this->assertContains('firstName', $transformed['required']);
    }

    public function testDescription(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['sjsform' => ['description' => $description = 'A word that references you in the hash of the world']]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));

        $this->translator->method('trans')
            ->will(
                $this->returnCallback(
                    function ($description) {
                        return $description;
                    }
                )
            );
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertArrayHasKey('description', $transformed['properties']['firstName']);
        $this->assertSame($description, $transformed['properties']['firstName']['description']);
    }

    public function testDescriptionFromFormHelp(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                [
                    'help' => $description = 'Some information for the field',
                ]
            );

        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $this->translator->method('trans')
            ->will(
                $this->returnCallback(
                    function ($description) {
                        return $description;
                    }
                )
            );
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertArrayHasKey('description', $transformed['properties']['firstName']);
        $this->assertSame($description, $transformed['properties']['firstName']['description']);
    }

    public function testDescriptionFromFormHelpOverriddenByLiformDescription(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                [
                    'help' => $help = 'Some information for the field',
                    'sjsform' => [
                        'description' => $description = 'This will be set',
                    ],
                ]
            );

        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $this->translator->method('trans')
            ->will(
                $this->returnCallback(
                    function ($description) {
                        return $description;
                    }
                )
            );
        $transformed = $transformer->transform($form);

        $this->assertArrayHasKey('description', $transformed['properties']['firstName']);
        $this->assertSame($description, $transformed['properties']['firstName']['description']);
    }

    public function testLabel(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['label' => 'a label']
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $this->translator
            ->expects($this->exactly(2))
            ->method('trans')
            ->willReturn('a label');
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertArrayHasKey('title', $transformed['properties']['firstName']);
        $this->assertEquals('a label', $transformed['properties']['firstName']['title']);
    }

    public function testWidget(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['sjsform' => ['widget' => 'my widget']]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertArrayHasKey('widget', $transformed['properties']['firstName']);
    }

    public function testWidgetViaTransformerDefinition(): void
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator), 'widg');
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertArrayHasKey('widget', $transformed['properties']['firstName']);
    }
}
