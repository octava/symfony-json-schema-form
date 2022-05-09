<?php

namespace Octava\SymfonyJsonSchemaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SymfonyJsonSchemaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\PhpFileLoader(
            $container,
            new \Symfony\Component\Config\FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.php');
    }
}
