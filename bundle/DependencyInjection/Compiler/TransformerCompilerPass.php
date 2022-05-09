<?php

namespace Octava\SymfonyJsonSchemaBundle\DependencyInjection\Compiler;

use Octava\SymfonyJsonSchemaForm\Resolver;
use Octava\SymfonyJsonSchemaForm\Transformer\TransformerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TransformerCompilerPass implements CompilerPassInterface
{
    protected const TRANSFORMER_TAG = 'sjsform.transformer';

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Resolver::class)) {
            return;
        }

        $resolver = $container->getDefinition(Resolver::class);

        foreach ($container->findTaggedServiceIds(self::TRANSFORMER_TAG) as $id => $attributes) {
            $transformer = $container->getDefinition($id);

            if (!isset(class_implements($transformer->getClass())[TransformerInterface::class])) {
                throw new \InvalidArgumentException(
                    sprintf(
                        "The service %s was tagged as a '%s' but does not implement the mandatory %s",
                        $id,
                        self::TRANSFORMER_TAG,
                        TransformerInterface::class
                    )
                );
            }

            foreach ($attributes as $attribute) {
                if (!isset($attribute['form_type'])) {
                    throw new \InvalidArgumentException(
                        sprintf(
                            "The service %s was tagged as a '%s' but does not specify the mandatory 'form_type' option.",
                            $id,
                            self::TRANSFORMER_TAG
                        )
                    );
                }

                $widget = null;

                if (isset($attribute['widget'])) {
                    $widget = $attribute['widget'];
                }

                $resolver->addMethodCall('setTransformer', [$attribute['form_type'], new Reference($id), $widget]);
            }
        }
    }
}
