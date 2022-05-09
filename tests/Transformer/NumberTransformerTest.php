<?php

namespace Octava\SymfonyJsonSchemaForm\Tests\Transformer;

use Octava\SymfonyJsonSchemaForm\Resolver;
use Octava\SymfonyJsonSchemaForm\Tests\SymfonyJsonSchemaFormTestCase;
use Octava\SymfonyJsonSchemaForm\Transformer\CompoundTransformer;
use Octava\SymfonyJsonSchemaForm\Transformer\NumberTransformer;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class NumberTransformerTest extends SymfonyJsonSchemaFormTestCase
{
    public function testPattern()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'somefield',
                NumberType::class,
                ['sjsform' => ['widget' => 'widget']]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('number', new NumberTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertEquals('number', $transformed['properties']['somefield']['type']);
        $this->assertEquals('widget', $transformed['properties']['somefield']['widget']);
    }
}
