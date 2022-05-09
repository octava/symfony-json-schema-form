<?php

namespace Octava\SymfonyJsonSchemaForm\Tests\Transformer;

use Octava\SymfonyJsonSchemaForm\Resolver;
use Octava\SymfonyJsonSchemaForm\Tests\SymfonyJsonSchemaFormTestCase;
use Octava\SymfonyJsonSchemaForm\Transformer\CompoundTransformer;
use Octava\SymfonyJsonSchemaForm\Transformer\StringTransformer;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CompoundTransformerTest extends SymfonyJsonSchemaFormTestCase
{
    public function testOrder()
    {
        $form = $this->factory->create(FormType::class)
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class);
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertEquals(1, $transformed['properties']['firstName']['propertyOrder']);
        $this->assertEquals(2, $transformed['properties']['secondName']['propertyOrder']);
    }
}
