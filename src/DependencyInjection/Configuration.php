<?php

namespace Torq\PimcoreWikiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('torq_pimcore_wiki');
        $treeBuilder->getRootNode()->children()->scalarNode('documentation_path')->defaultValue(
            '%kernel.project_dir%/docs',
        )->info('Path to the directory containing markdown documentation files')->end()->end()->end();
        return $treeBuilder;
    }
}
