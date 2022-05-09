<?php

namespace Octava\SymfonyJsonSchemaForm\Tests\Transformer;

use Octava\SymfonyJsonSchemaForm\Resolver;
use Octava\SymfonyJsonSchemaForm\Tests\SymfonyJsonSchemaFormTestCase;
use Octava\SymfonyJsonSchemaForm\Transformer\CompoundTransformer;
use Octava\SymfonyJsonSchemaForm\Transformer\StringTransformer;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StringTransformerTest extends SymfonyJsonSchemaFormTestCase
{
    public function testPattern()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['attr' => ['pattern' => '.{5,}' ]]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertEquals('.{5,}', $transformed['properties']['firstName']['pattern']);
    }
}
