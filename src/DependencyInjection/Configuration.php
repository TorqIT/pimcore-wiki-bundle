<?php

namespace TorqIT\WikiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('wiki_bundle');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('documentation_path')
                    ->defaultValue('%kernel.project_dir%/docs')
                    ->info('Path to the directory containing markdown documentation files')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
