<?php

declare(strict_types=1);

namespace AssoConnect\PHPDateBundle\DependencyInjection;

use AssoConnect\PHPDate\Doctrine\DBAL\Types\AbsoluteDateType;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PHPDateExtension extends Extension
{
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('doctrine', [
            'dbal' => [
                'types' => [
                    'date_absolute' => AbsoluteDateType::class,
                ],
                'mapping_types' => [
                    'date_absolute' => 'string',
                ],
            ]
        ]);
    }


    public function load(array $configs, ContainerBuilder $container)
    {
        // Loading config.yml file
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
    }
}