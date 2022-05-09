<?php

namespace Octava\SymfonyJsonSchemaBundle\DependencyInjection\Compiler;

use Octava\SymfonyJsonSchemaForm\SJSForm;
use Octava\SymfonyJsonSchemaForm\Transformer\ExtensionInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExtensionCompilerPass implements CompilerPassInterface
{
    protected const EXTENSION_TAG = 'sjsform.extension';

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(SJSForm::class)) {
            return;
        }

        $definition = $container->getDefinition(SJSForm::class);

        foreach ($container->findTaggedServiceIds(self::EXTENSION_TAG) as $id => $attributes) {
            $extension = $container->getDefinition($id);

            if (!isset(class_implements($extension->getClass())[ExtensionInterface::class])) {
                throw new \InvalidArgumentException(
                    sprintf(
                        "The service %s was tagged as a '%s' but does not implement the mandatory %s",
                        $id,
                        self::EXTENSION_TAG,
                        ExtensionInterface::class
                    )
                );
            }

            $definition->addMethodCall('addExtension', [$extension]);
        }
    }
}
