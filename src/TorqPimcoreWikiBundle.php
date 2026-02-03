<?php

namespace Torq\PimcoreWikiBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\PimcoreBundleAdminClassicInterface;
use Pimcore\Extension\Bundle\Traits\BundleAdminClassicTrait;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

class TorqPimcoreWikiBundle extends AbstractPimcoreBundle implements PimcoreBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;

    public function getJsPaths(): array
    {
        return [
            '/bundles/torqpimcorewiki/addLinkToAdminToolbar.js',
        ];
    }

    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('documentation_path')
                    ->defaultValue('%kernel.project_dir%/docs')
                    ->info('Path to the directory containing markdown documentation files')
                ->end()
            ->end()
        ->end();
    }
}
