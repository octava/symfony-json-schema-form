<?php

namespace Octava\SymfonyJsonSchemaBundle;

use Octava\SymfonyJsonSchemaBundle\DependencyInjection\Compiler\ExtensionCompilerPass;
use Octava\SymfonyJsonSchemaBundle\DependencyInjection\Compiler\TransformerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfonyJsonSchemaBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TransformerCompilerPass());
        $container->addCompilerPass(new ExtensionCompilerPass());
    }
}
